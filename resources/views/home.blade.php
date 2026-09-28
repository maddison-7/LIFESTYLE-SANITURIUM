<x-layouts.app
    :title="null"
    description="Professional and confidential healthcare services focused on reproductive health, urinary system health, consultation, testing, treatment and follow-up care for men and women."
>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800">
        <div class="absolute inset-0 opacity-40" aria-hidden="true">
            <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-primary-500/80 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-16 h-[26rem] w-[26rem] rounded-full bg-primary-700/80 blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.16),transparent_28%)]"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8 lg:py-28">
            <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="max-w-3xl">
                    <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-[11px] font-semibold tracking-[0.22em] text-primary-100 uppercase shadow-[0_12px_30px_rgba(15,23,42,0.18)] backdrop-blur-sm">
                        {{ __('Private Care. Elevated Wellness.') }}
                    </p>
                    <h1 class="mt-6 text-4xl font-black leading-[1.02] tracking-[-0.05em] text-white sm:text-5xl lg:text-6xl">
                        Compassionate care that feels truly personal.
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-primary-100/90 text-pretty">
                        {{ __('Modern, discreet healthcare for reproductive wellness, urinary health, clinical guidance, diagnostics, treatment, and long-term follow-up support for men and women.') }}
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">
                        <x-btn :href="route('appointments.create')" variant="primary" size="lg">
                            {{ __('Book an Appointment') }}
                        </x-btn>
                        <x-btn :href="whatsapp_link()" variant="whatsapp" size="lg" target="_blank" rel="noopener noreferrer">
                            {{ __('Chat on WhatsApp') }}
                        </x-btn>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <x-btn :href="route('services.index')" variant="outline-light" size="md">
                            {{ __('Explore Our Services') }}
                        </x-btn>
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-2 text-xs font-medium uppercase tracking-[0.18em] text-primary-100">
                            {{ __('Confidential Care') }}
                        </span>
                    </div>
                </div>

                <div class="relative lg:justify-self-end">
                    <div class="relative overflow-hidden rounded-[2rem] border border-white/15 bg-white/8 p-5 shadow-[0_36px_70px_rgba(2,6,23,0.45)] backdrop-blur-md">
                        <div class="rounded-[1.5rem] bg-white/8 p-5 ring-1 ring-white/10">
                            <div class="flex items-center justify-between text-primary-100">
                                <span class="text-xs font-semibold uppercase tracking-[0.2em]">Care Snapshot</span>
                                <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-[10px] font-semibold text-emerald-200">Open Today</span>
                            </div>

                            <div class="mt-6 space-y-4">
                                <div class="rounded-2xl bg-white/8 p-4">
                                    <p class="text-xs uppercase tracking-[0.18em] text-primary-100/80">Consultation</p>
                                    <p class="mt-2 text-2xl font-bold text-white">Same-day care</p>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-2xl bg-primary-50/10 p-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-primary-100/80">Services</p>
                                        <p class="mt-2 text-xl font-bold text-white">7 specialised pathways</p>
                                    </div>
                                    <div class="rounded-2xl bg-primary-50/10 p-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-primary-100/80">Support</p>
                                        <p class="mt-2 text-xl font-bold text-white">Private & secure</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Overview --}}
    <section class="py-24 sm:py-28 lg:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                :eyebrow="__('What We Offer')"
                :title="__('Comprehensive, Confidential Healthcare Services')"
                :description="__('Our clinic focuses on reproductive health, urinary system health, consultation, testing, treatment and follow-up care.')"
            />

            <div class="mt-16 grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($services as $service)
                    <x-service-card :service="$service" />
                @empty
                    <p class="col-span-full text-center text-gray-500">{{ __('Services will be listed here shortly.') }}</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="bg-surface-50 py-24 sm:py-28 lg:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                :eyebrow="__('Why Choose Us')"
                :title="__('Care Centred Around You')"
                :description="__('Trusted support, discreet care, and a patient-first approach designed around your wellbeing.')"
            />

            <div class="mt-16 grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $whyItems = [
                        ['title' => __('Professional Care'), 'desc' => __('Delivered by a dedicated healthcare team focused on your wellbeing.'), 'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                        ['title' => __('Privacy & Confidentiality'), 'desc' => __('Your health information and visits are treated with strict confidentiality.'), 'icon' => 'M12 3.75c-4 1-7 3-7 6.75 0 5 4 8.25 7 9.75 3-1.5 7-4.75 7-9.75 0-3.75-3-5.75-7-6.75Z'],
                        ['title' => __('Patient-Centred Care'), 'desc' => __('Every consultation is tailored around your individual needs and concerns.'), 'icon' => 'M16.5 18.75h-9A2.25 2.25 0 0 1 5.25 16.5v-9A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25Z'],
                        ['title' => __('Follow-Up Support'), 'desc' => __('Structured follow-up care for patients continuing their treatment journey.'), 'icon' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99'],
                    ];
                @endphp

                @foreach ($whyItems as $item)
                    <div class="rounded-[1.75rem] border border-surface-200 bg-white p-6 text-center shadow-[0_18px_40px_rgba(15,23,42,0.04)] transition-all duration-200 hover:-translate-y-1 hover:shadow-[0_22px_50px_rgba(21,128,108,0.10)] sm:text-left">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 text-primary-700 shadow-inner sm:mx-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-gray-900">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Appointment CTA --}}
    <section class="bg-gradient-to-r from-primary-600 to-primary-700 py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-black tracking-[-0.04em] text-white sm:text-4xl">
                &ldquo;{{ __('Ready to speak with our healthcare team?') }}&rdquo;
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base text-primary-100 sm:text-lg">
                {{ __('Book a private consultation or connect with our team for personalised guidance and support.') }}
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <x-btn :href="route('appointments.create')" variant="secondary" size="lg">
                    {{ __('Book an Appointment') }}
                </x-btn>
                <x-btn :href="whatsapp_link()" variant="whatsapp" size="lg" target="_blank" rel="noopener noreferrer">
                    {{ __('Chat on WhatsApp') }}
                </x-btn>
            </div>
        </div>
    </section>

    {{-- Branches Preview --}}
    <section class="py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                :eyebrow="__('Our Branches')"
                :title="__('Find a Branch Near You')"
            />

            <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-3xl mx-auto">
                @forelse ($branches as $branch)
                    <x-branch-card :branch="$branch" />
                @empty
                    <p class="col-span-full text-center text-gray-500">{{ __('Branch information coming soon.') }}</p>
                @endforelse
            </div>

            <div class="mt-10 text-center">
                <x-btn :href="route('branches.index')" variant="ghost">
                    {{ __('View All Branches') }}
                </x-btn>
            </div>
        </div>
    </section>

    {{-- Health Education preview --}}
    <section class="py-20 sm:py-24 bg-surface-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-heading
                :eyebrow="__('Health Education')"
                :title="__('Learn About Your Health')"
                :description="$articles->isNotEmpty() ? __('Recent articles from our team.') : __('Educational articles from our team are on the way.')"
            />

            @if ($articles->isNotEmpty())
                <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($articles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
                <div class="mt-10 text-center">
                    <x-btn :href="route('education.index')" variant="ghost">
                        {{ __('View All Articles') }}
                    </x-btn>
                </div>
            @else
                <div class="mt-14 rounded-2xl border border-dashed border-surface-300 bg-white p-12 text-center max-w-2xl mx-auto">
                    <p class="text-gray-600">
                        {{ __("We're preparing health education articles covering men's health, women's health, reproductive health and general wellness. Check back soon.") }}
                    </p>
                    <div class="mt-6">
                        <x-btn :href="route('education.index')" variant="ghost">
                            {{ __('Visit Health Education') }}
                        </x-btn>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Get in Touch') }}</h2>
            <p class="mt-3 text-gray-600">{{ __('Our team is ready to answer your questions.') }}</p>

            <div class="mt-8 flex flex-wrap justify-center gap-x-8 gap-y-3 text-lg font-semibold text-primary-700">
                @if (!empty($siteSettings['phone_primary'] ?? null))
                    <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings['phone_primary']) }}" class="hover:text-primary-800">{{ $siteSettings['phone_primary'] }}</a>
                @endif
                @if (!empty($siteSettings['phone_secondary'] ?? null))
                    <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings['phone_secondary']) }}" class="hover:text-primary-800">{{ $siteSettings['phone_secondary'] }}</a>
                @endif
                @if (!empty($siteSettings['phone_tertiary'] ?? null))
                    <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings['phone_tertiary']) }}" class="hover:text-primary-800">{{ $siteSettings['phone_tertiary'] }}</a>
                @endif
            </div>

            <div class="mt-8">
                <x-btn :href="whatsapp_link()" variant="whatsapp" size="lg" target="_blank" rel="noopener noreferrer">
                    {{ __('Chat on WhatsApp') }}
                </x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
