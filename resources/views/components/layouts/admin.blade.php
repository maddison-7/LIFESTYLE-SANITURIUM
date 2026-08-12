@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    @include('partials.theme-init')
    <title>{{ $title }} | Admin | Lifestyle Sanitarium Clinic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-surface-50 text-gray-900">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        {{-- Desktop sidebar --}}
        <aside class="hidden lg:block w-72 shrink-0">
            <x-admin.sidebar />
        </aside>

        {{-- Mobile sidebar drawer --}}
        <div x-cloak x-show="sidebarOpen" class="lg:hidden fixed inset-0 z-40" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-black/50"></div>
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="relative z-50 h-full w-72"
                @click.outside="sidebarOpen = false"
            >
                <button @click="sidebarOpen = false" type="button" class="absolute -right-11 top-4 flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white" aria-label="Close menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
                <x-admin.sidebar />
            </div>
        </div>

        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <x-admin.topbar :title="$title" />

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-6">
                        <x-alert type="success">{{ session('success') }}</x-alert>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6">
                        <x-alert type="error">{{ session('error') }}</x-alert>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
