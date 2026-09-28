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

<header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-surface-200/80 bg-white/95 shadow-[0_10px_26px_rgba(15,23,42,0.08)] backdrop-blur-xl transition-all duration-300 ease-out">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-2 focus:top-2 focus:z-[60] focus:rounded-lg focus:bg-primary-600 focus:px-4 focus:py-2 focus:text-white">
        {{ __('Skip to content') }}
    </a>

    <nav class="mx-auto max-w-7xl px-3 py-2 sm:px-5 lg:px-6" aria-label="Primary">
        <div class="flex h-16 items-center justify-between gap-4 sm:h-20 lg:gap-6">
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-2.5 py-2 transition-transform duration-200 hover:-translate-y-0.5">
                @if (!empty($siteSettings['logo_path'] ?? null))
                    <img src="{{ asset('storage/' . $siteSettings['logo_path']) }}" alt="{{ $siteSettings['clinic_name'] ?? 'MADILA LIFESTYLE CLINIC' }}" class="h-10 w-auto rounded-full ring-2 ring-primary-100 shadow-sm">
                @else
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary-600 to-primary-500 text-lg font-bold text-white shadow-[0_8px_20px_rgba(21,128,108,0.25)]">M</span>
                @endif
                <span class="leading-tight">
                    <span class="block text-sm font-black tracking-tight text-gray-900 transition-colors duration-200 group-hover:text-primary-700 sm:text-base">MADILA LIFESTYLE</span>
                    <span class="block text-[10px] font-semibold uppercase tracking-[0.24em] text-primary-600">Clinic</span>
                </span>
            </a>

            <div class="hidden flex-1 justify-center lg:flex">
                <ul class="flex items-center gap-1 rounded-full border border-surface-200 bg-surface-50 p-1 shadow-[0_8px_24px_rgba(15,23,42,0.06)]">
                    @foreach ($navItems as $item)
                        <li>
                            <a
                                href="{{ route($item['route']) }}"
                                class="inline-flex items-center rounded-full px-3.5 py-2 text-sm font-medium transition-all duration-200 ease-out hover:-translate-y-0.5 hover:shadow-[0_8px_18px_rgba(21,128,108,0.12)] {{ request()->routeIs($item['route']) ? 'bg-primary-600 text-white shadow-[0_10px_22px_rgba(21,128,108,0.24)]' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}"
                                @if (request()->routeIs($item['route'])) aria-current="page" @endif
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="hidden items-center gap-2 lg:flex">
                <div class="flex items-center gap-1 rounded-full border border-surface-200 bg-surface-50 p-1 shadow-sm">
                    <x-language-switcher />
                </div>
                <div class="flex items-center gap-1 rounded-full border border-surface-200 bg-surface-50 p-1 shadow-sm">
                    <x-theme-toggle />
                </div>
                <a href="{{ auth('patient')->check() ? route('portal.dashboard') : route('portal.login') }}" class="rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition-all duration-200 ease-out hover:-translate-y-0.5 hover:bg-primary-50 hover:text-primary-700">
                    {{ auth('patient')->check() ? __('My Account') : __('Patient Login') }}
                </a>
                <x-btn :href="route('appointments.create')" variant="primary" size="sm" class="whitespace-nowrap shadow-[0_12px_22px_rgba(21,128,108,0.22)] transition-transform duration-200 hover:-translate-y-0.5">
                    {{ __('Book Appointment') }}
                </x-btn>
            </div>

            <div class="flex items-center gap-1.5 lg:hidden">
                <div class="rounded-full border border-surface-200 bg-surface-50 p-1 shadow-sm">
                    <x-language-switcher />
                </div>
                <div class="rounded-full border border-surface-200 bg-surface-50 p-1 shadow-sm">
                    <x-theme-toggle />
                </div>

                <button
                    @click="open = !open"
                    type="button"
                    class="inline-flex items-center justify-center rounded-full border border-surface-200 bg-white p-2.5 text-gray-700 shadow-sm transition-base hover:border-primary-200 hover:bg-primary-50 hover:text-primary-700"
                    :aria-expanded="open"
                    aria-label="Toggle navigation menu"
                >
                    <svg x-cloak x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-cloak x-show="open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
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
            class="border-t border-surface-200 py-3 lg:hidden"
        >
            <ul class="flex flex-col gap-1.5">
                @foreach ($navItems as $item)
                    <li>
                        <a
                            href="{{ route($item['route']) }}"
                            class="block rounded-xl px-3 py-2.5 text-base font-medium transition-base {{ request()->routeIs($item['route']) ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-700' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="mt-3 space-y-2.5">
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
