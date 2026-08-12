<x-layouts.app
    title="Services"
    description="Explore Lifestyle Sanitarium Clinic's services covering men's and women's reproductive health, urinary system health, consultation, testing, treatment and follow-up care."
>
    <section class="bg-primary-950 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-primary-300">{{ __('Our Services') }}</p>
            <h1 class="mt-3 text-4xl sm:text-5xl font-extrabold text-white">{{ __('Healthcare Services') }}</h1>
            <p class="mt-4 text-primary-100 max-w-2xl mx-auto text-pretty">
                {{ __('Professional and confidential care across reproductive health, urinary system health, consultation, testing, treatment and follow-up.') }}
            </p>
        </div>
    </section>

    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if ($categories->isNotEmpty())
                <div class="flex flex-wrap gap-2 justify-center">
                    <a
                        href="{{ route('services.index') }}"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-base {{ !$activeCategory ? 'bg-primary-600 text-white' : 'bg-surface-100 text-gray-700 hover:bg-primary-50' }}"
                    >
                        {{ __('All Services') }}
                    </a>
                    @foreach ($categories as $category)
                        <a
                            href="{{ route('services.index', ['category' => $category]) }}"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-base {{ $activeCategory === $category ? 'bg-primary-600 text-white' : 'bg-surface-100 text-gray-700 hover:bg-primary-50' }}"
                        >
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($services as $service)
                    <x-service-card :service="$service" />
                @empty
                    <div class="col-span-full text-center py-16">
                        <p class="text-gray-500">{{ __('No services found in this category yet.') }}</p>
                        <div class="mt-6">
                            <x-btn :href="route('services.index')" variant="ghost">{{ __('View All Services') }}</x-btn>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="pb-20">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Not sure which service you need?') }}</h2>
            <p class="mt-3 text-gray-600">{{ __("Speak with our team and we'll guide you to the right care.") }}</p>
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <x-btn :href="route('appointments.create')" variant="primary">{{ __('Book an Appointment') }}</x-btn>
                <x-btn :href="whatsapp_link()" variant="whatsapp" target="_blank" rel="noopener noreferrer">{{ __('Chat on WhatsApp') }}</x-btn>
            </div>
        </div>
    </section>
</x-layouts.app>
