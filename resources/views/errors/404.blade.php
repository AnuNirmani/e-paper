<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-gray-800">404</h1>
        <p class="text-2xl font-semibold text-gray-600 mb-4">Page Not Found</p>
        <p class="text-gray-500 mb-8">The page you are looking for doesn't exist or has been moved.</p>
        <a href="{{ url('/') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
            Go to Homepage
        </a>
    </div>
</body>
</html>
