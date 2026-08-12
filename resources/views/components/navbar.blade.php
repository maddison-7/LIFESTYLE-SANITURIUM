@php
    $navItems = [
        ['label' => __('Home'), 'route' => 'home'],
        ['label' => __('About'), 'route' => 'about'],
        ['label' => __('Services'), 'route' => 'services.index'],
        ['label' => __('Healthcare Team'), 'route' => 'team.index'],
        ['label' => __('Health Education'), 'route' => 'education.index'],
        ['label' => __('Branches'), 'route' => 'branches.index'],
        ['label' => __('Contact'), 'route' => 'contact'],
    ];
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-surface-200">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-[60] bg-primary-600 text-white px-4 py-2 rounded-lg">
        {{ __('Skip to content') }}
    </a>

    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Primary">
        <div class="flex h-18 sm:h-20 items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 py-2">
                @if (!empty($siteSettings['logo_path'] ?? null))
                    <img src="{{ asset('storage/' . $siteSettings['logo_path']) }}" alt="{{ $siteSettings['clinic_name'] ?? 'Lifestyle Sanitarium Clinic' }}" class="h-10 w-auto">
                @else
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-600 text-white font-bold text-lg">L</span>
                @endif
                <span class="leading-tight">
                    <span class="block text-base sm:text-lg font-bold text-gray-900">Lifestyle Sanitarium</span>
                    <span class="block text-[11px] sm:text-xs font-medium text-primary-600 tracking-wide">CLINIC</span>
                </span>
            </a>

            <ul class="hidden lg:flex items-center gap-1">
                @foreach ($navItems as $item)
                    <li>
                        <a
                            href="{{ route($item['route']) }}"
                            class="px-3.5 py-2 rounded-lg text-sm font-medium transition-base {{ request()->routeIs($item['route']) ? 'text-primary-700 bg-primary-50' : 'text-gray-700 hover:text-primary-700 hover:bg-primary-50' }}"
                            @if (request()->routeIs($item['route'])) aria-current="page" @endif
                        >
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="hidden lg:flex items-center gap-3">
                <x-language-switcher />
                <x-theme-toggle />
                <a href="{{ auth('patient')->check() ? route('portal.dashboard') : route('portal.login') }}" class="text-sm font-medium text-gray-700 hover:text-primary-700">
                    {{ auth('patient')->check() ? __('My Account') : __('Patient Login') }}
                </a>
                <x-btn :href="route('appointments.create')" variant="primary" size="sm">
                    {{ __('Book Appointment') }}
                </x-btn>
            </div>

            <div class="flex items-center gap-1 lg:hidden">
                <x-language-switcher />
                <x-theme-toggle />

                <button
                    @click="open = !open"
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-gray-700 hover:bg-primary-50"
                    :aria-expanded="open"
                    aria-label="Toggle navigation menu"
                >
                    <svg x-cloak x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-cloak x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div
            x-cloak
            x-show="open"
            x-transition
            @click.outside="open = false"
            class="lg:hidden border-t border-surface-200 py-3"
        >
            <ul class="flex flex-col gap-1">
                @foreach ($navItems as $item)
                    <li>
                        <a
                            href="{{ route($item['route']) }}"
                            class="block px-3 py-2.5 rounded-lg text-base font-medium {{ request()->routeIs($item['route']) ? 'text-primary-700 bg-primary-50' : 'text-gray-700 hover:bg-primary-50' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="mt-3 px-1 space-y-2.5">
                <x-btn :href="route('appointments.create')" variant="primary" class="w-full">
                    {{ __('Book Appointment') }}
                </x-btn>
                <x-btn :href="auth('patient')->check() ? route('portal.dashboard') : route('portal.login')" variant="secondary" class="w-full">
                    {{ auth('patient')->check() ? __('My Account') : __('Patient Login') }}
                </x-btn>
            </div>
        </div>
    </nav>
</header>
