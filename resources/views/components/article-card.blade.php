@props(['article'])

<article class="flex flex-col rounded-2xl border border-surface-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition-base">
    <a href="{{ route('education.show', $article) }}" class="block aspect-video bg-surface-100 overflow-hidden">
        @if ($article->featuredImageUrl())
            <img src="{{ $article->featuredImageUrl() }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
        @else
            <div class="flex h-full w-full items-center justify-center text-primary-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-6">
        <p class="text-xs font-semibold uppercase tracking-wide text-primary-600">{{ $article->category }}</p>
        <h3 class="mt-2 text-lg font-bold text-gray-900">
            <a href="{{ route('education.show', $article) }}" class="hover:text-primary-700">{{ $article->title }}</a>
        </h3>
        <p class="mt-2 text-sm text-gray-600 leading-relaxed flex-1">{{ $article->excerptOrSummary() }}</p>

        <div class="mt-4 flex items-center justify-between">
            <span class="text-xs text-gray-400">{{ $article->published_at?->format('d M Y') }}</span>
            <a href="{{ route('education.show', $article) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                {{ __('Read More') }} &rarr;
            </a>
        </div>
    </div>
</article>
