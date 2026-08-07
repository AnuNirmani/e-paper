<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renew Subscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h1 class="text-2xl font-bold">Renew Subscription</h1>
            <p class="text-blue-100 text-sm">Customer details loaded from secure email link</p>
        </div>

        <div class="p-6">
            @if(!$prefill)
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4">
                    No prefilled customer details found. Please use your renewal email link.
                </div>
            @else
                @if($message)
                    <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6">
                        {{ $message }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h2 class="font-semibold text-gray-900 mb-3">Customer Details</h2>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Order ID</dt><dd class="font-medium text-gray-900">{{ $prefill['order_id'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">First Name</dt><dd class="font-medium text-gray-900">{{ $prefill['first_name'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Last Name</dt><dd class="font-medium text-gray-900">{{ $prefill['last_name'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900 break-all">{{ $prefill['email'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Phone</dt><dd class="font-medium text-gray-900">{{ $prefill['phone'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">WhatsApp</dt><dd class="font-medium text-gray-900">{{ $prefill['whatsapp_number'] ?? '-' }}</dd></div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h2 class="font-semibold text-gray-900 mb-3">Delivery / Subscription</h2>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Address</dt><dd class="font-medium text-gray-900 text-right">{{ $prefill['address'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">City</dt><dd class="font-medium text-gray-900">{{ $prefill['city'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Province</dt><dd class="font-medium text-gray-900">{{ $prefill['province'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Zip</dt><dd class="font-medium text-gray-900">{{ $prefill['zip_code'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Country</dt><dd class="font-medium text-gray-900">{{ $prefill['country'] ?? '-' }}</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Duration</dt><dd class="font-medium text-gray-900">{{ $prefill['duration'] ?? '-' }} month(s)</dd></div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-900">Publication Details</h2>
                    </div>

                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left bg-gray-50 border-b border-gray-200">
                                <th class="px-4 py-3 font-semibold text-gray-700">Publication</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 text-right">Renew Amount (LKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($total = 0)
                            @forelse(($prefill['publications'] ?? []) as $publication)
                                @php($total += (float) ($publication['price'] ?? 0))
                                <tr class="border-b border-gray-100">
                                    <td class="px-4 py-3 text-gray-900">{{ $publication['name'] ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-900 text-right">{{ number_format((float) ($publication['price'] ?? 0), 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">No linked publications found for this customer.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-blue-50">
                                <td class="px-4 py-3 font-semibold text-blue-900">Total Amount</td>
                                <td class="px-4 py-3 font-bold text-blue-900 text-right">{{ number_format($total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
