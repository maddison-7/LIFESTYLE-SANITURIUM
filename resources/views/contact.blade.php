<x-layouts.app
    title="Contact"
    description="Contact Lifestyle Sanitarium Clinic by phone or WhatsApp, or find us at our Buguruni Malapa branch in Dar es Salaam."
>
    <section class="bg-primary-950 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-primary-300">{{ __('Contact Us') }}</p>
            <h1 class="mt-3 text-4xl sm:text-5xl font-extrabold text-white">{{ __('Get in Touch') }}</h1>
            <p class="mt-4 text-primary-100 max-w-2xl mx-auto text-pretty">
                {{ __('Our team is available to answer your questions and help you book an appointment.') }}
            </p>
        </div>
    </section>

    <section class="py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-5 gap-12">

            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Phone') }}</h2>
                    <ul class="mt-3 space-y-2">
                        @foreach (['phone_primary', 'phone_secondary', 'phone_tertiary'] as $key)
                            @if (!empty($siteSettings[$key] ?? null))
                                <li>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings[$key]) }}" class="text-primary-700 font-semibold hover:text-primary-800">
                                        {{ $siteSettings[$key] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-gray-900">WhatsApp</h2>
                    <p class="mt-3">
                        <x-btn :href="whatsapp_link()" variant="whatsapp" target="_blank" rel="noopener noreferrer">
                            {{ __('Chat on WhatsApp') }}
                        </x-btn>
                    </p>
                </div>

                @if (!empty($siteSettings['email'] ?? null))
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ __('Email') }}</h2>
                        <a href="mailto:{{ $siteSettings['email'] }}" class="mt-3 block text-primary-700 font-semibold hover:text-primary-800">
                            {{ $siteSettings['email'] }}
                        </a>
                    </div>
                @endif

                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('Main Branch Address') }}</h2>
                    <p class="mt-3 text-gray-600">{{ $siteSettings['address'] ?? 'Buguruni Malapa, Dar es Salaam, Tanzania' }}</p>
                </div>
            </div>

            <div class="lg:col-span-3">
                <h2 class="text-lg font-bold text-gray-900">{{ __('Find Us') }}</h2>

                @if (!empty($siteSettings['google_maps_url'] ?? null))
                    <div class="mt-4 aspect-video w-full overflow-hidden rounded-2xl border border-surface-200">
                        <iframe
                            src="{{ $siteSettings['google_maps_url'] }}"
                            class="h-full w-full"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lifestyle Sanitarium Clinic location"
                        ></iframe>
                    </div>
                @else
                    <div class="mt-4 rounded-2xl border border-dashed border-surface-300 bg-surface-50 p-10 text-center">
                        <p class="text-gray-600">
                            {{ $siteSettings['address'] ?? 'Buguruni Malapa, Dar es Salaam, Tanzania' }}
                        </p>
                        <p class="mt-2 text-sm text-gray-500">{{ __('Map location will be added soon.') }}</p>
                    </div>
                @endif

                <div class="mt-8">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">{{ __('Branches') }}</h3>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($branches as $branch)
                            <div class="rounded-xl border border-surface-200 p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-semibold text-gray-900">{{ $branch->name }}</p>
                                    <span class="shrink-0 text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $branch->isOpen() ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ __($branch->statusLabel()) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ $branch->location }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
