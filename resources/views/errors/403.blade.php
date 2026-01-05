<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-gray-800">403</h1>
        <p class="text-2xl font-semibold text-gray-600 mb-4">Access Denied</p>
        <p class="text-gray-500 mb-8">You don't have permission to access this resource.</p>
        <a href="{{ url('/dashboard') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
            Go to Dashboard
        </a>
    </div>
</body>
</html>
