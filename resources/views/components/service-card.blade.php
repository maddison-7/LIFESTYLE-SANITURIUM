@props(['service', 'icon' => null])

<article class="group flex flex-col rounded-2xl border border-surface-200 bg-white p-6 shadow-sm hover:shadow-md hover:border-primary-200 transition-base">
    @if ($service->image)
        <div class="h-12 w-12 rounded-xl overflow-hidden">
            <img src="{{ $service->imageUrl() }}" alt="{{ $service->name }}" class="h-full w-full object-cover">
        </div>
    @else
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
            {!! $icon ?? '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>' !!}
        </div>
    @endif

    <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-primary-600">{{ $service->category }}</p>
    <h3 class="mt-1 text-lg font-bold text-gray-900">{{ $service->name }}</h3>
    <p class="mt-2 text-sm text-gray-600 leading-relaxed flex-1">{{ $service->description }}</p>

    <a
        href="{{ route('appointments.create', ['service' => $service->slug]) }}"
        class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-700 hover:text-primary-800"
    >
        {{ __('Book Appointment') }}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
        </svg>
    </a>
</article>
