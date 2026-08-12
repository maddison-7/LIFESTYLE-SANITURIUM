<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied | Lifestyle Sanitarium Clinic</title>
    @vite(['resources/css/app.css'])
</head>
<body class="antialiased bg-white text-gray-900 min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-primary-600 text-white font-bold text-xl">L</span>
        <p class="mt-6 text-sm font-semibold uppercase tracking-wide text-primary-600">Error 403</p>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Access Denied</h1>
        <p class="mt-3 text-gray-600">
            You don't have permission to view this page. If you believe this is a mistake, contact a system administrator.
        </p>
        <a href="{{ url('/admin') }}" class="mt-8 inline-flex items-center justify-center rounded-lg bg-primary-600 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-700 transition-colors">
            Back to Dashboard
        </a>
    </div>
</body>
</html>
