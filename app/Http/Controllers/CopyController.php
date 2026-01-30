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
        // Get only active customers with valid subscriptions
        $customers = Customer::where('status', 1)
            ->where(function($query) {
                $query->whereNull('ending_date')
                      ->orWhere('ending_date', '>=', now()->startOfDay());
            })
            ->orderBy('first_name')
            ->get();
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
        // Get only active customers with valid subscriptions
        $customers = Customer::where('status', 1)
            ->where(function($query) {
                $query->whereNull('ending_date')
                      ->orWhere('ending_date', '>=', now()->startOfDay());
            })
            ->orderBy('first_name')
            ->get();
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
            'file'           => 'required|file|mimes:pdf|max:20480',
            'message'        => 'nullable|string'
        ]);

        /* BULK SEND LOGIC - Move up to get Publication Name for Watermark if needed */
        $publication = Publication::findOrFail($request->publication_id);

        /* FILE UPLOAD */
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();

        $useWatermark = $request->boolean('watermark');
        $folder = $useWatermark
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

        // Get active customers for this publication with valid subscriptions
        $customers = $publication->customers()
            ->where('customers.status', 1)
            ->where(function($query) {
                $query->whereNull('customers.ending_date')
                      ->orWhere('customers.ending_date', '>=', now()->startOfDay());
            })
            ->get();

        \Illuminate\Support\Facades\Log::info("UploadStore: Found " . $customers->count() . " active customers with valid subscriptions for publication " . $request->publication_id);

        $sentCount = 0;
        $skippedExpired = 0;
        foreach ($customers as $customer) {
            // Double-check subscription is still active
            if (!$customer->isSubscriptionActive()) {
                \Illuminate\Support\Facades\Log::warning("UploadStore: Skipping customer {$customer->id} - subscription expired on {$customer->ending_date}");
                $skippedExpired++;
                continue;
            }

            if (!empty($customer->whatsapp_number)) {
                $caption = $ultraMsgService->getDailyPaperCaption($customer->first_name);
                
                // Prepare Watermark Args
                $watermarkText = null;
                $outputDir = null;

                 if ($useWatermark) {
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

        $message = "File uploaded. Sent to $sentCount customers.";
        if ($skippedExpired > 0) {
            $message .= " ($skippedExpired skipped - subscription expired)";
        }

        return redirect()->route('copies.index')
                         ->with('success', $message);
    }

    public function destroy($id)
    {
        $copy = Copy::findOrFail($id);
        $copy->delete();

        return redirect()->route('copies.index')
                         ->with('success', 'Copy deleted successfully');
    }
}
