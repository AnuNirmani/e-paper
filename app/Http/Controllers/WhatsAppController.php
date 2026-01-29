<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    private function getInstanceId()
    {
        return env('ULTRAMSG_INSTANCE_ID', 'instance157661');
    }

    private function getToken()
    {
        return env('ULTRAMSG_TOKEN', '6as99r2fzw1mxtsx');
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
}