<x-layouts.app
    :title="$article->title"
    :description="$article->excerptOrSummary()"
>
    <article class="py-16 sm:py-20">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <nav class="text-sm text-gray-500 mb-6">
                <a href="{{ route('education.index') }}" class="hover:text-primary-700">{{ __('Health Education') }}</a>
                <span class="mx-1.5">/</span>
                <span class="text-gray-700">{{ $article->category }}</span>
            </nav>

            <p class="text-sm font-semibold uppercase tracking-wide text-primary-600">{{ $article->category }}</p>
            <h1 class="mt-2 text-3xl sm:text-4xl font-bold text-gray-900 text-balance">{{ $article->title }}</h1>
            <p class="mt-3 text-sm text-gray-500">{{ __('Published :date', ['date' => $article->published_at?->format('d M Y')]) }}</p>

            @if ($article->featuredImageUrl())
                <div class="mt-8 aspect-video rounded-2xl overflow-hidden bg-surface-100">
                    <img src="{{ $article->featuredImageUrl() }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
                </div>
            @endif

            <div class="mt-8 text-gray-700 leading-relaxed text-pretty">
                @foreach (explode("\n\n", trim($article->content)) as $paragraph)
                    @if (trim($paragraph) !== '')
                        <p class="mb-4">{{ trim($paragraph) }}</p>
                    @endif
                @endforeach
            </div>

            <div class="mt-10 rounded-2xl bg-primary-50 border border-primary-100 p-6 text-center">
                <p class="text-sm text-primary-800 leading-relaxed">
                    {{ setting('medical_disclaimer') }}
                </p>
            </div>

            <div class="mt-8 flex flex-wrap gap-4">
                <x-btn :href="route('appointments.create')" variant="primary">{{ __('Book an Appointment') }}</x-btn>
                <x-btn :href="whatsapp_link()" variant="whatsapp" target="_blank" rel="noopener noreferrer">{{ __('Chat on WhatsApp') }}</x-btn>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="pb-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('Related Articles') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($related as $relatedArticle)
                        <x-article-card :article="$relatedArticle" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
