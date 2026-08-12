<x-layouts.app
    title="Healthcare Team"
    description="Meet the Lifestyle Sanitarium Clinic healthcare team."
>
    <section class="bg-primary-950 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-primary-300">{{ __('Our Team') }}</p>
            <h1 class="mt-3 text-4xl sm:text-5xl font-extrabold text-white">{{ __('Healthcare Team') }}</h1>
            <p class="mt-4 text-primary-100 max-w-2xl mx-auto text-pretty">
                {{ __("Meet the professionals behind Lifestyle Sanitarium Clinic's care.") }}
            </p>
        </div>
    </section>

    @if ($staff->isNotEmpty())
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($staff as $member)
                        <x-staff-card :member="$member" />
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <x-coming-soon
            :eyebrow="__('Healthcare Team')"
            :title="__('Our Team Profiles Are Coming Soon')"
            :message="__('We\'re preparing profiles for our healthcare team. In the meantime, our team is available to assist you by phone or WhatsApp.')"
        >
            <x-btn :href="route('contact')" variant="secondary">{{ __('Contact Us') }}</x-btn>
            <x-btn :href="whatsapp_link()" variant="whatsapp" target="_blank" rel="noopener noreferrer">{{ __('Chat on WhatsApp') }}</x-btn>
        </x-coming-soon>
    @endif
</x-layouts.app>
