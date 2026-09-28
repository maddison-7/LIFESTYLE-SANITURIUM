@props(['service', 'icon' => null])

<article class="group relative flex h-full flex-col overflow-hidden rounded-[1.5rem] border border-surface-200 bg-white p-6 shadow-[0_20px_45px_rgba(15,23,42,0.05)] transition-all duration-300 ease-out hover:-translate-y-1.5 hover:border-primary-200 hover:shadow-[0_28px_60px_rgba(21,128,108,0.13)]">
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary-400 via-[#C8A96B] to-primary-700 opacity-80"></div>
    <div class="mb-7 flex items-center justify-between gap-3">
        @if ($service->image)
            <div class="h-14 w-14 overflow-hidden rounded-2xl ring-1 ring-surface-200 shadow-sm">
                <img src="{{ $service->imageUrl() }}" alt="{{ $service->name }}" class="h-full w-full object-cover">
            </div>
        @else
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-50 via-white to-[#f5efe3] text-primary-700 ring-1 ring-primary-100 shadow-sm">
                {!! $icon ?? '<svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>' !!}
            </div>
        @endif
        <span class="rounded-full border border-[#decda9] bg-[#fbf7ed] px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.16em] text-[#80652c]">{{ $service->category }}</span>
    </div>

    <h3 class="text-xl font-bold tracking-[-0.025em] text-gray-900">{{ $service->name }}</h3>
    <p class="mt-3 flex-1 text-[15px] leading-7 text-gray-600">{{ $service->description }}</p>

    <a
        href="{{ route('appointments.create', ['service' => $service->slug]) }}"
        class="mt-7 inline-flex items-center gap-2 self-start rounded-full border border-primary-200 bg-primary-50 px-4 py-2.5 text-sm font-bold text-primary-800 transition-all duration-200 hover:border-primary-300 hover:bg-primary-100 hover:text-primary-900"
    >
        {{ __('Book Appointment') }}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
        </svg>
    </a>
</article>
