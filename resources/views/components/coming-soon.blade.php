@props(['eyebrow', 'title', 'message'])

<section class="py-24">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 text-primary-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
        </div>
        <p class="mt-6 text-sm font-semibold uppercase tracking-wide text-primary-600">{{ $eyebrow }}</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-bold text-gray-900">{{ $title }}</h1>
        <p class="mt-4 text-gray-600 text-pretty">{{ $message }}</p>

        <div class="mt-8 flex flex-wrap justify-center gap-4">
            {{ $slot }}
        </div>
    </div>
</section>
