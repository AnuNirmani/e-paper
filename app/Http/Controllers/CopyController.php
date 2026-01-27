<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Copy;
use App\Models\Customer;
use App\Models\Publication;
use App\Jobs\SendUltraMsgPdfJob;

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
        set_time_limit(0); // Unlimited execution time for large uploads and multiple API calls
        ini_set('memory_limit', '512M'); // Increase memory limit for file processing

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
                
                // Prepare Watermark Args
                $watermarkText = null;
                $outputDir = null;

                if ($request->has('watermark') && $request->watermark == '1') {
                     $watermarkText = $customer->first_name . ' ' . $customer->last_name . " copy ";
                     $outputDir = public_path($folder);
                }

                SendUltraMsgPdfJob::dispatch(
                    $customer->id,
                    $customer->whatsapp_number,
                    $originalFullPath,
                    $filename,
                    $caption,
                    $request->publication_id,
                    $watermarkText,
                    $outputDir
                );
                
                $sentCount++;

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
