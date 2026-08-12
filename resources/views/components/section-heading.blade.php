@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'align' => 'center',
])

<div class="{{ $align === 'center' ? 'text-center mx-auto' : 'text-left' }} max-w-2xl">
    @if ($eyebrow)
        <p class="text-sm font-semibold uppercase tracking-wide text-primary-600 mb-3">{{ $eyebrow }}</p>
    @endif
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 text-balance">{{ $title }}</h2>
    @if ($description)
        <p class="mt-4 text-base sm:text-lg text-gray-600 text-pretty">{{ $description }}</p>
    @endif
</div>
