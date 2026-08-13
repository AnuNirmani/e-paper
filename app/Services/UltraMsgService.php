<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Utils\PhoneNumberFormatter;

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
     * Format phone number to E.164 format required by WhatsApp API
     * 
     * @param string $phoneNumber The phone number to format
     * @param string|null $countryCode Optional country code (e.g., 'US', 'GB', 'UG')
     * @return string Formatted phone number in E.164 format
     */
    private function formatPhoneNumber($phoneNumber, $countryCode = null)
    {
        return PhoneNumberFormatter::ensureE164($phoneNumber, $countryCode);
    }

    /**
     * Send a document via WhatsApp
     *
     * @param string $to Recipient WhatsApp number
     * @param string $document URL (http...) or Base64 string
     * @param string $filename Filename to display
     * @param string $caption Caption for the message
     * @param string|null $countryCode Optional country code for phone number formatting
     * @return array|null Response body or null on failure
     */
    public function sendDocument($to, $document, $filename, $caption = '', $countryCode = null)
    {
        if (!$this->instanceId || !$this->token) {
            Log::error('UltraMsg credentials not found in env.');
            return null;
        }

        // Format phone number to E.164 format for WhatsApp API
        $formattedTo = $this->formatPhoneNumber($to, $countryCode);
        Log::info("Phone number formatting: {$to} -> {$formattedTo}");

        $timeoutSeconds = (int) env('ULTRAMSG_HTTP_TIMEOUT', 600);

        try {
            $response = Http::withOptions([
                'verify' => false,
                'connect_timeout' => 30,
                'timeout' => $timeoutSeconds,
            ])
                ->asForm()
                ->post("https://api.ultramsg.com/{$this->instanceId}/messages/document", [
                'token' => $this->token,
                'to' => $formattedTo,
                'document' => $document,
                'filename' => $filename,
                'caption' => $caption,
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

    /**
     * Send a text message via WhatsApp
     *
     * @param string $to Recipient WhatsApp number
     * @param string $message Message text to send
     * @param string|null $countryCode Optional country code for phone number formatting
     * @return array|null Response body or null on failure
     */
    public function sendMessage($to, $message, $countryCode = null)
    {
        if (!$this->instanceId || !$this->token) {
            Log::error('UltraMsg credentials not found in env.');
            return null;
        }

        // Format phone number to E.164 format for WhatsApp API
        $formattedTo = $this->formatPhoneNumber($to, $countryCode);
        Log::info("Phone number formatting: {$to} -> {$formattedTo}");

        try {
            $response = Http::withOptions([
                'verify' => false,
                'connect_timeout' => 15,
                'timeout' => 60,
            ])
                ->asForm()
                ->post("https://api.ultramsg.com/{$this->instanceId}/messages/chat", [
                'token' => $this->token,
                'to' => $formattedTo,
                'body' => $message,
            ]);

            Log::info("UltraMsg Send Message to {$to}", [
                'status' => $response->status(),
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
}
