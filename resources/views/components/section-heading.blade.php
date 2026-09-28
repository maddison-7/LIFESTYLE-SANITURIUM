@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'align' => 'center',
])

<div class="{{ $align === 'center' ? 'mx-auto text-center' : 'text-left' }} max-w-3xl">
    @if ($eyebrow)
        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-primary-600">{{ $eyebrow }}</p>
    @endif
    <h2 class="font-serif text-4xl font-semibold leading-[0.98] tracking-[-0.02em] text-gray-900 sm:text-5xl lg:text-[3.25rem] text-balance">{{ $title }}</h2>
    @if ($description)
        <p class="mt-4 text-base text-gray-600 text-pretty sm:text-lg">{{ $description }}</p>
    @endif
</div>
