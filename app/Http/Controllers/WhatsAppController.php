<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Customer;
use App\Services\UltraMsgService;

class WhatsAppController extends Controller
{
    private function getInstanceId()
    {
        return env('ULTRAMSG_INSTANCE_ID', 'instance15092');
    }

    private function getToken()
    {
        return env('ULTRAMSG_TOKEN', 'erj9te9sg3m8n8lv');
    }

    public function showQRPage()
    {
        return view('whatsapp.qr-scan');
    }

    public function getQRCode()
    {
        $instanceId = $this->getInstanceId();
        $token = $this->getToken();
        
        try {
            $params = array('token' => $token);
            $url = "https://api.ultramsg.com/{$instanceId}/instance/qr?" . http_build_query($params);
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            
            if ($err) {
                \Log::error('cURL Error', ['error' => $err]);
                return response()->json(['error' => "cURL Error: " . $err], 500);
            }
            
            // Check if response is JSON (error message or status)
            $json = json_decode($response, true);
            if ($json && isset($json['error'])) {
                \Log::info('UltraMsg API Response', $json);
                
                // Check if the error is because instance is already authenticated
                if (strpos($json['error'], 'not equal') !== false || 
                    strpos($json['error'], 'instance status') !== false) {
                    return response()->json([
                        'already_connected' => true,
                        'message' => 'WhatsApp is already connected!'
                    ], 200);
                }
                
                // Check if the error is about non-payment/stopped instance
                if (strpos($json['error'], 'Stopped') !== false || 
                    strpos($json['error'], 'non-payment') !== false) {
                    return response()->json([
                        'error' => 'Your instance has been Stopped due to non-payment. you can activate this instance by extending your subscription.' . "\n" . 
                                   'Or go to "https://user.ultramsg.com/app/instances/instances.php" and extend the trial'
                    ], 400);
                }
                
                return response()->json($json, 400);
            }
            
            // Response is PNG image - convert to base64 data URL
            $base64 = base64_encode($response);
            $dataUrl = 'data:image/png;base64,' . $base64;
            
            \Log::info('QR Code fetched successfully', [
                'size' => strlen($response),
                'http_code' => $httpCode
            ]);
            
            return response()->json([
                'qrCode' => $dataUrl,
                'success' => true
            ]);
            
        } catch (\Exception $e) {
            \Log::error('QR Code Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkStatus()
    {
        $instanceId = $this->getInstanceId();
        $token = $this->getToken();
        
        try {
            $url = "https://api.ultramsg.com/{$instanceId}/instance/status";
            
            $response = Http::get($url, [
                'token' => $token  // Pass token as query parameter
            ]);

            \Log::info('UltraMsg Status Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'error' => 'Failed to check status',
                'message' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            \Log::error('Status Check Error', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        $instanceId = $this->getInstanceId();
        $token = $this->getToken();
        
        try {
            $params = array('token' => $token);
            $url = "https://api.ultramsg.com/{$instanceId}/instance/logout?" . http_build_query($params);
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            
            if ($err) {
                \Log::error('Logout cURL Error', ['error' => $err]);
                return response()->json([
                    'success' => false,
                    'message' => "cURL Error: " . $err
                ], 500);
            }
            
            // Check if response is JSON
            $json = json_decode($response, true);
            
            \Log::info('WhatsApp Logout Response', [
                'http_code' => $httpCode,
                'response' => $json ?? $response
            ]);
            
            if ($httpCode === 200) {
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully logged out from WhatsApp'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => $json['message'] ?? 'Failed to logout'
            ], $httpCode);
            
        } catch (\Exception $e) {
            \Log::error('Logout Error', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send subscription ending notification to a customer
     *
     * @param int $customerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSubscriptionEndingNotification($customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);
            
            if (!$customer->whatsapp_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer does not have a WhatsApp number'
                ], 400);
            }

            // Calculate days remaining
            $daysRemaining = $customer->ending_date ? 
                now()->startOfDay()->diffInDays($customer->ending_date->startOfDay(), false) : 0;
            
            // Format the message using Template Service
            $messageTemplateService = app(\App\Services\MessageTemplateService::class);
            $customerName = trim($customer->first_name . ' ' . $customer->last_name);
            $endingDateStr = $customer->ending_date ? 
                $customer->ending_date->format('d/m/Y') : 'N/A';

            // Determine template key based on days remaining
            if ($daysRemaining > 3) {
                $key = 'subscription_notify_7days';
                $daysText = $daysRemaining . ' days';
            } elseif ($daysRemaining > 0) {
                $key = 'subscription_notify_3days';
                $daysText = $daysRemaining . ' days';
            } elseif ($daysRemaining == 0) {
                $key = 'subscription_notify_today';
                $daysText = 'today';
            } else {
                $key = 'subscription_notify_expired';
                $daysText = 'expired';
            }

            $newspapersTaken = $customer->publications->pluck('name')->implode(', ');

            $message = $messageTemplateService->buildSubscriptionMessage(
                $key,
                '', // No hardcoded fallback — edit templates at /messages
                [
                    'name' => $customerName,
                    'ending_date' => $endingDateStr,
                    'days_remaining' => $daysText,
                    'newspapers_taken' => $newspapersTaken,
                ]
            );

            // Send via UltraMsg
            $ultraMsgService = new UltraMsgService();
            $result = $ultraMsgService->sendMessage($customer->whatsapp_number, $message);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subscription ending notification sent successfully to ' . $customerName,
                    'response' => $result
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send WhatsApp message. Please check logs.'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Send Subscription Notification Error', [
                'error' => $e->getMessage(),
                'customer_id' => $customerId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
