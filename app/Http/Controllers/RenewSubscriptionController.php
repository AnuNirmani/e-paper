<?php

namespace App\Http\Controllers;

use App\Models\OneTimeAccessLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenewSubscriptionController extends Controller
{
    public function fromEmail(Request $request)
    {
        $sid = $this->extractSid($request);

        if ($sid === '') {
            return $this->invalid('Missing sid token.');
        }

        $target = trim((string) env('RENEW_SUBSCRIPTION_SUBSCRIBE_URL', ''));
        if ($target === '') {
            $target = url('/subscribe');
        }

        $separator = str_contains($target, '?') ? '&' : '?';
        return redirect()->away($target.$separator.'sid='.urlencode($sid));
    }

    public function prefill(Request $request)
    {
        $sid = $this->extractSid($request);

        if ($sid === '') {
            return response()->json([
                'success' => false,
                'message' => 'Missing sid token.',
            ], 422);
        }

        [$prefill, $error] = $this->resolvePrefillFromSid($sid, true);

        if ($error !== null) {
            return response()->json([
                'success' => false,
                'message' => $error,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $prefill,
        ]);
    }

    private function resolvePrefillFromSid(string $sid, bool $consume): array
    {
        $link = OneTimeAccessLink::with(['customer.publications'])
            ->where('token', $sid)
            ->first();

        if (!$link) {
            return [null, 'Invalid sid token.'];
        }

        if ($link->expires_at && now()->greaterThan($link->expires_at)) {
            return [null, 'This renew link has expired.'];
        }

        if ($consume) {
            $updated = DB::transaction(function () use ($link) {
                return OneTimeAccessLink::where('id', $link->id)
                    ->whereNull('used_at')
                    ->update(['used_at' => now()]);
            });

            if ($updated === 0) {
                return [null, 'This renew link has already been used.'];
            }
        }

        $customer = $link->customer;
        if (!$customer) {
            return [null, 'No customer is attached to this token.'];
        }

        $prefill = [
            'customer_id' => $customer->id,
            'order_id' => $customer->order_id,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'whatsapp_number' => $customer->whatsapp_number,
            'address' => $customer->address,
            'city' => $customer->city,
            'province' => $customer->province,
            'zip_code' => $customer->zip_code,
            'country' => $customer->country,
            'duration' => $customer->duration,
            'starting_date' => optional($customer->starting_date)?->format('Y-m-d'),
            'ending_date' => optional($customer->ending_date)?->format('Y-m-d'),
            'publications' => $customer->publications
                ->map(function ($publication) {
                    return [
                        'id' => $publication->id,
                        'name' => $publication->name,
                        'price' => (float) ($publication->pivot->price ?? 0),
                    ];
                })
                ->values()
                ->all(),
        ];

        return [$prefill, null];
    }

    private function extractSid(Request $request): string
    {
        $sid = trim((string) $request->query('sid', ''));

        if ($sid === '') {
            foreach (array_keys($request->query()) as $queryKey) {
                if (str_starts_with($queryKey, 'sid=')) {
                    $sid = substr($queryKey, 4);
                    break;
                }
            }
        }

        return trim(urldecode($sid), " \t\n\r\0\x0B\"'");
    }

    private function invalid(string $message)
    {
        return view('renew-subscription.invalid-link', [
            'message' => $message,
        ], 422);
    }
}
