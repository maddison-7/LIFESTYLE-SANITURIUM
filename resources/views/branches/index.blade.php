<x-layouts.app
    title="Branches"
    description="Find Lifestyle Sanitarium Clinic branches, including our open Buguruni Malapa branch in Dar es Salaam and our upcoming USA-River branch in Arusha."
>
    <section class="bg-primary-950 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-primary-300">{{ __('Our Branches') }}</p>
            <h1 class="mt-3 text-4xl sm:text-5xl font-extrabold text-white">{{ __('Find a Branch Near You') }}</h1>
        </div>
    </section>

    <section class="py-20">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse ($branches as $branch)
                    <x-branch-card :branch="$branch" />
                @empty
                    <p class="col-span-full text-center text-gray-500">{{ __('Branch information coming soon.') }}</p>
                @endforelse
            </div>

            <div class="mt-14 text-center">
                <h2 class="text-xl font-bold text-gray-900">{{ __("Can't visit us in person?") }}</h2>
                <p class="mt-2 text-gray-600">{{ __('Reach out to our team on WhatsApp or by phone.') }}</p>
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <x-btn :href="route('contact')" variant="secondary">{{ __('View Contact Details') }}</x-btn>
                    <x-btn :href="whatsapp_link()" variant="whatsapp" target="_blank" rel="noopener noreferrer">{{ __('Chat on WhatsApp') }}</x-btn>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
