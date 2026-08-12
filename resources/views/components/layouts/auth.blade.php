@props(['title' => 'Admin Login', 'footerText' => 'Staff access only.'])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    @include('partials.theme-init')
    <title>{{ $title }} | Lifestyle Sanitarium Clinic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-surface-50 text-gray-900 min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="flex items-center justify-center gap-1.5 mb-4">
            <x-language-switcher />
            <x-theme-toggle />
        </div>

        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-primary-600 text-white font-bold text-lg">L</span>
                <span class="leading-tight text-left">
                    <span class="block text-base font-bold text-gray-900">Lifestyle Sanitarium</span>
                    <span class="block text-[11px] font-medium text-primary-600 tracking-wide">CLINIC</span>
                </span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-surface-200 shadow-sm p-8">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-xs text-gray-500">
            &copy; {{ now()->year }} Lifestyle Sanitarium Clinic. {{ $footerText }}
        </p>
    </div>
</body>
</html>
