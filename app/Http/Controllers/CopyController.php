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

        $file->move(public_path($folder), $filename);
        
        $fullPath = public_path($folder . '/' . $filename);

        // Apply Watermark if requested
        if ($request->has('watermark') && $request->watermark == '1') {
            try {
                // Use Publication Name or default text
                $watermarkText = $publication->name ?? 'e-Paper';
                
                // Add watermark - this might overwrite or create new file
                // We pass the folder as output. SDK likely keeps filename.
                $newPath = $watermarkService->addWatermark($fullPath, $watermarkText, public_path($folder));
                
                if ($newPath && file_exists($newPath)) {
                    $fullPath = $newPath;
                    $filename = basename($fullPath);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Watermarking failed: " . $e->getMessage());
                // Proceed with original file or fail? Proceeding seems safer to not break flow, but maybe notify?
            }
        }

        // Prepare Base64 Data for UltraMsg (Avoids localhost public URL issue)
        $fileData = file_get_contents($fullPath);
        $base64Data = base64_encode($fileData);
        // Assuming PDF properly
        $documentBody = "data:application/pdf;base64," . $base64Data;

        // Get active customers for this publication
        $customers = $publication->customers()->where('customers.status', 1)->get();

        \Illuminate\Support\Facades\Log::info("UploadStore: Found " . $customers->count() . " active customers for publication " . $request->publication_id);

        $sentCount = 0;
        foreach ($customers as $customer) {
            if (!empty($customer->whatsapp_number)) {
                $caption = $ultraMsgService->getDailyPaperCaption($customer->first_name);
                
                // Get copy count, default to 1 if 0 or null
                $copyCount = $customer->pivot->attachment_count ?? 1;
                if ($copyCount < 1) $copyCount = 1;

                for ($i = 0; $i < $copyCount; $i++) {
                    // Send via UltraMsg
                    $ultraMsgService->sendDocument(
                        $customer->whatsapp_number,
                        $documentBody,
                        $filename,
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
