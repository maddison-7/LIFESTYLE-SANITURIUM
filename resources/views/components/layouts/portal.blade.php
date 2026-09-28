@props(['title' => 'My Account'])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    @include('partials.theme-init')
    <title>{{ $title }} | Patient Portal | MADILA LIFESTYLE CLINIC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-surface-50 text-gray-900 min-h-screen flex flex-col">
    <header class="sticky top-0 z-30 bg-white border-b border-surface-200">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-600 text-white font-bold">M</span>
                    <span class="leading-tight">
                        <span class="block text-sm font-bold text-gray-900">MADILA LIFESTYLE</span>
                        <span class="block text-[10px] font-medium text-primary-600 tracking-wide">PATIENT PORTAL</span>
                    </span>
                </a>

                <div class="flex items-center gap-1.5">
                    <x-language-switcher />
                    <x-theme-toggle />

                    @auth('patient')
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="flex items-center gap-2 rounded-lg py-1.5 pl-1.5 pr-2.5 hover:bg-surface-100">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-primary-700 font-semibold text-sm">
                                    {{ Str::of(auth('patient')->user()->name)->substr(0, 1)->upper() }}
                                </span>
                                <span class="hidden sm:block text-sm font-semibold text-gray-900">{{ auth('patient')->user()->name }}</span>
                            </button>
                            <div x-cloak x-show="open" x-transition @click.outside="open = false" class="absolute right-0 mt-2 w-48 rounded-lg border border-surface-200 bg-white py-1.5 shadow-lg">
                                <a href="{{ route('portal.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-surface-50">{{ __('Dashboard') }}</a>
                                <a href="{{ route('portal.appointments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-surface-50">{{ __('My Appointments') }}</a>
                                <a href="{{ route('portal.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-surface-50">{{ __('Profile') }}</a>
                                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-surface-50">{{ __('Public Site') }}</a>
                                <form method="POST" action="{{ route('portal.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-surface-50">{{ __('Logout') }}</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-10">
            @if (session('success'))
                <div class="mb-6"><x-alert type="success">{{ session('success') }}</x-alert></div>
            @endif
            @if (session('error'))
                <div class="mb-6"><x-alert type="error">{{ session('error') }}</x-alert></div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <footer class="border-t border-surface-200 bg-white py-6 text-center text-xs text-gray-500">
        &copy; {{ now()->year }} MADILA LIFESTYLE CLINIC. <a href="{{ route('privacy-policy') }}" class="hover:text-primary-700">Privacy Policy</a>
    </footer>
</body>
</html>
