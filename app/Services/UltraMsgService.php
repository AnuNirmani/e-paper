<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UltraMsgService
{
    protected $instanceId;
    protected $token;

    public function __construct()
    {
        $this->instanceId = env('ULTRAMSG_INSTANCE_ID');
        $this->token = env('ULTRAMSG_TOKEN');
    }

    /**
     * Send a document via WhatsApp
     *
     * @param string $to Recipient WhatsApp number
     * @param string $document URL (http...) or Base64 string
     * @param string $filename Filename to display
     * @param string $caption Caption for the message
     * @return array|null Response body or null on failure
     */
    public function sendDocument($to, $document, $filename, $caption = '')
    {
        if (!$this->instanceId || !$this->token) {
            Log::error('UltraMsg credentials not found in env.');
            return null;
        }

        try {
            // Using withOptions to disable SSL verification (verify => false)
            $response = Http::withOptions(['verify' => false])
                ->asForm()
                ->post("https://api.ultramsg.com/{$this->instanceId}/messages/document", [
                'token' => $this->token,
                'to' => $to,
                'document' => $document,
                'filename' => $filename,
                'caption' => $caption
            ]);

            // Log without the massive body if it's base64
            $isBase64 = strlen($document) > 1000;
            Log::info("UltraMsg Send Attempt to {$to}", [
                'status' => $response->status(),
                'document_type' => $isBase64 ? 'Base64 Data' : 'URL',
                'body_response' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('UltraMsg API Error: ' . $response->body());
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('UltraMsg Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the default caption for daily paper
     * 
     * @param string $firstName
     * @return string
     */
    public function getDailyPaperCaption($firstName)
    {
        return "Hello " . $firstName . ", this is today paper";
    }
}
