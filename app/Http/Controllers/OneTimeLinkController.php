<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OneTimeAccessLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OneTimeLinkController extends Controller
{
    public function generate(Request $request, string $orderId)
    {
        $customer = Customer::where('order_id', trim($orderId))->first();

        if (!$customer) {
            abort(404, 'Customer not found for the given order ID.');
        }

        $token = $this->makeToken($customer->order_id);

        $record = OneTimeAccessLink::create([
            'customer_id' => $customer->id,
            'order_id' => $customer->order_id,
            'token' => $token,
            'expires_at' => now()->addDay(),
        ]);

        $generatedUrl = $this->buildRenewUrl($record->token);

        if ($request->boolean('show')) {
            return view('email-notifier.generated-link', [
                'token' => $record->token,
                'generatedUrl' => $generatedUrl,
                'customer' => $customer,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'token' => $record->token,
                'url' => $generatedUrl,
                'order_id' => $customer->order_id,
                'expires_at' => optional($record->expires_at)?->toDateTimeString(),
            ]);
        }

        return response($record->token, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function consume(string $token)
    {
        return redirect()->to($this->buildRenewUrl($token));
    }

    private function buildRenewUrl(string $token): string
    {
        $configuredBase = trim((string) env('RENEW_SUBSCRIPTION_SUBSCRIBE_URL', ''));

        if ($configuredBase === '') {
            $configuredBase = trim((string) env('RENEW_SUBSCRIPTION_EMAIL_URL', ''));
        }

        if ($configuredBase !== '') {
            $separator = str_contains($configuredBase, '?') ? '&' : '?';
            return $configuredBase.$separator.'sid='.urlencode($token);
        }

        return route('renew-subscription.subscribe', ['sid' => $token]);
    }

    private function makeToken(string $orderId): string
    {
        $entropy = $orderId.'|'.Str::uuid()->toString().'|'.microtime(true);
        return hash('sha512', $entropy).base64_encode(random_bytes(32));
    }
}
