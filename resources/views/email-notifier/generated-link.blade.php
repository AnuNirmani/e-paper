<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated One-Time Link</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-3xl rounded-xl shadow-lg p-6 md:p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">One-Time Link Generated</h1>
        <p class="text-gray-600 mb-6">Order ID: <span class="font-semibold">{{ $customer->order_id }}</span></p>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-5">
            <p class="text-xs text-gray-500 mb-2">Generated token</p>
            <p class="text-sm break-all font-mono text-gray-800">{{ $token }}</p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-xs text-blue-600 mb-2">One-time URL</p>
            <p class="text-sm break-all font-mono text-blue-900">{{ $generatedUrl }}</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ $generatedUrl }}" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                Open One-Time Link
            </a>
            <button onclick="copyLink()" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300">
                Copy Link
            </button>
        </div>
    </div>

    <script>
        function copyLink() {
            const url = @json($generatedUrl);
            navigator.clipboard.writeText(url).then(() => alert('Link copied to clipboard'));
        }
    </script>
</body>
</html>
