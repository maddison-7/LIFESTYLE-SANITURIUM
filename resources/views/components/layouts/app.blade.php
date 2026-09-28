@props([
    'title' => null,
    'description' => 'MADILA LIFESTYLE CLINIC offers professional and confidential healthcare services focused on reproductive health, urinary system health, consultation, testing, treatment and follow-up care.',
])

@php
    $pageTitle = $title ? $title . ' | MADILA LIFESTYLE CLINIC' : 'MADILA LIFESTYLE CLINIC | Afya Bora, Maisha Bora.';
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-init')
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="MADILA LIFESTYLE CLINIC">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">

    <link rel="canonical" href="{{ url()->current() }}">
    @if (!empty($siteSettings['favicon_path'] ?? null))
        <link rel="icon" href="{{ asset('storage/' . $siteSettings['favicon_path']) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-surface-50 text-gray-900 min-h-screen flex flex-col selection:bg-primary-200 selection:text-primary-950">

    <x-navbar />

    @if (session('success'))
        <div class="mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 pt-4">
            <x-alert type="success">{{ session('success') }}</x-alert>
        </div>
    @endif

    @if (session('error'))
        <div class="mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 pt-4">
            <x-alert type="error">{{ session('error') }}</x-alert>
        </div>
    @endif

    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    <x-footer />
    <x-whatsapp-button />

</body>
</html>
