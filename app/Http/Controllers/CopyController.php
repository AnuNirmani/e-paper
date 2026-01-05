<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Copy;
use App\Models\Customer;
use App\Models\Publication;

class CopyController extends Controller
{
    public function index()
    {
        $copies = Copy::getAllCopies()->paginate(20);
        return view('copies.index', compact('copies'));
    }

    public function create()
    {
        $customers = Customer::where('status', 1)->orderBy('first_name')->get();
        $publications = Publication::where('status', 1)->orderBy('name')->get();

        return view('copies.create', compact('customers', 'publications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required',
            'publication_id' => 'required',
            'message'        => 'nullable'
        ]);

        Copy::createCopy($request->all());

        return redirect()->route('copies.index')
                         ->with('success', 'Copy added successfully');
    }

    /* ===============================
       UPLOAD FUNCTIONALITY
    =============================== */
    public function upload()
    {
        $customers = Customer::where('status', 1)->orderBy('first_name')->get();
        $publications = Publication::getActivePublications();
        return view('copies.upload', compact('customers', 'publications'));
    }

    public function uploadStore(Request $request, \App\Services\UltraMsgService $ultraMsgService, \App\Services\WatermarkService $watermarkService)
    {
        $request->validate([
            'customer_id'    => 'nullable',
            'publication_id' => 'required',
            'file'           => 'required|file',
            'message'        => 'nullable|string'
        ]);

        /* BULK SEND LOGIC - Move up to get Publication Name for Watermark if needed */
        $publication = Publication::findOrFail($request->publication_id);

        /* FILE UPLOAD */
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();

        $folder = $request->has('watermark')
            ? 'uploads/with_watermark'
            : 'uploads/without_watermark';
        
        // Ensure folder exists
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }

        $file->move(public_path($folder), $filename);
        
        $originalFullPath = public_path($folder . '/' . $filename);

        // Prepare Original Base64 Data (Default)
        $originalFileData = file_get_contents($originalFullPath);
        $originalBase64Data = base64_encode($originalFileData);
        $originalDocumentBody = "data:application/pdf;base64," . $originalBase64Data;

        // Get active customers for this publication
        $customers = $publication->customers()->where('customers.status', 1)->get();

        \Illuminate\Support\Facades\Log::info("UploadStore: Found " . $customers->count() . " active customers for publication " . $request->publication_id);

        $sentCount = 0;
        foreach ($customers as $customer) {
            if (!empty($customer->whatsapp_number)) {
                $caption = $ultraMsgService->getDailyPaperCaption($customer->first_name);
                
                // Determine Document Body (Personalized vs Original)
                $documentBodyToSend = $originalDocumentBody;
                $filenameToSend = $filename;
                $tempPersonalizedPath = null;

                if ($request->has('watermark') && $request->watermark == '1') {
                    try {
                        // Personalized Watermark Text
                        $watermarkText = $customer->first_name . ' ' . $customer->last_name;
                        
                        // Output name for this customer (avoid collision)
                        $personalizedFilename = "{$customer->id}_" . time(); // SDK appends extension usually, but let's be safe. 
                        // Actually setOutputFilename expects name, SDK handles extension? Checking Task.php... 
                        // setOutputFilename($filename) sets $this->output_filename. 
                        // If I don't give extension, iLovePDF might add it. Let's provide it to be safe if SDK allows.
                        // Wait, previous code used time() . "pdf".
                        
                        $personalizedFilename = "{$customer->id}_" . time();
                        $outputDir = public_path($folder); // Re-added missing definition

                        \Illuminate\Support\Facades\Log::info("Watermarking for Customer {$customer->id}. OutputDir: $outputDir, Filename: $personalizedFilename");

                        // Add watermark
                        $newPath = $watermarkService->addWatermark($originalFullPath, $watermarkText, $outputDir, $personalizedFilename);
                        
                        if ($newPath && file_exists($newPath)) {
                            // Convert personalized file to base64
                            $pData = file_get_contents($newPath);
                            $pBase64 = base64_encode($pData);
                            $documentBodyToSend = "data:application/pdf;base64," . $pBase64;
                            $tempPersonalizedPath = $newPath;
                            
                            // Optional: Use personalized filename in WhatsApp? 
                            // Usually keeping original filename is better for user experience, 
                            // but we can change it if needed. Let's keep original filename.
                        }
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Watermarking failed for customer {$customer->id}: " . $e->getMessage());
                        // Fallback to original document
                    }
                }

                // Get copy count, default to 1 if 0 or null
                $copyCount = $customer->pivot->attachment_count ?? 1;
                if ($copyCount < 1) $copyCount = 1;

                for ($i = 0; $i < $copyCount; $i++) {
                    // Send via UltraMsg
                    $ultraMsgService->sendDocument(
                        $customer->whatsapp_number,
                        $documentBodyToSend,
                        $filenameToSend,
                        $caption
                    );

                    // Create Copy Record for each customer
                    Copy::createCopy([
                        'customer_id'    => $customer->id,
                        'publication_id' => $request->publication_id,
                        'message'        => $caption
                    ]);
                    
                    $sentCount++;
                }

                // Clean up personalized file to save space
                if ($tempPersonalizedPath && file_exists($tempPersonalizedPath)) {
                     // Adding a small delay or check might be good, but synchronous tasks usually safe to delete.
                     // However, file_get_contents reads it into memory, so we can delete.
                     @unlink($tempPersonalizedPath);
                }

            } else {
                 \Illuminate\Support\Facades\Log::warning("UploadStore: Customer {$customer->id} has no WhatsApp number.");
            }
        }

        return redirect()->route('copies.index')
                         ->with('success', "File uploaded. Sent to $sentCount customers.");
    }

    public function destroy($id)
    {
        $copy = Copy::findOrFail($id);
        $copy->delete();

        return redirect()->route('copies.index')
                         ->with('success', 'Copy deleted successfully');
    }
}
