<x-layouts.app
    title="Health Education"
    description="Health education articles from Lifestyle Sanitarium Clinic on men's health, women's health, reproductive health and general wellness."
>
    <section class="bg-primary-950 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-primary-300">{{ __('Health Education') }}</p>
            <h1 class="mt-3 text-4xl sm:text-5xl font-extrabold text-white">{{ __('Health Education') }}</h1>
            <p class="mt-4 text-primary-100 max-w-2xl mx-auto text-pretty">
                {{ __('General health information from our team on reproductive health, urinary system health and wellness.') }}
            </p>
        </div>
    </section>

    @if ($articles->isEmpty() && !$activeCategory)
        <x-coming-soon
            :eyebrow="__('Health Education')"
            :title="__('Health Articles Are Coming Soon')"
            :message="__('We\'re preparing educational content covering men\'s health, women\'s health, reproductive health, urinary system health and general wellness. Check back soon.')"
        >
            <x-btn :href="route('services.index')" variant="secondary">{{ __('Explore Our Services') }}</x-btn>
            <x-btn :href="whatsapp_link()" variant="whatsapp" target="_blank" rel="noopener noreferrer">{{ __('Chat on WhatsApp') }}</x-btn>
        </x-coming-soon>
    @else
        <section class="py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                @if ($categories->isNotEmpty())
                    <div class="flex flex-wrap gap-2 justify-center">
                        <a
                            href="{{ route('education.index') }}"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-base {{ !$activeCategory ? 'bg-primary-600 text-white' : 'bg-surface-100 text-gray-700 hover:bg-primary-50' }}"
                        >
                            {{ __('All Articles') }}
                        </a>
                        @foreach ($categories as $category)
                            <a
                                href="{{ route('education.index', ['category' => $category]) }}"
                                class="px-4 py-2 rounded-full text-sm font-semibold transition-base {{ $activeCategory === $category ? 'bg-primary-600 text-white' : 'bg-surface-100 text-gray-700 hover:bg-primary-50' }}"
                            >
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($articles as $article)
                        <x-article-card :article="$article" />
                    @empty
                        <div class="col-span-full text-center py-16">
                            <p class="text-gray-500">{{ __('No articles found in this category yet.') }}</p>
                            <div class="mt-6">
                                <x-btn :href="route('education.index')" variant="ghost">{{ __('View All Articles') }}</x-btn>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if ($articles->hasPages())
                    <div class="mt-12">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>
        </section>
    @endif
</x-layouts.app>
