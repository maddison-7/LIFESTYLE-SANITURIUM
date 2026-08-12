@props(['member'])

<article class="rounded-2xl border border-surface-200 bg-white p-6 text-center shadow-sm">
    @if ($member->photoUrl())
        <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="mx-auto h-24 w-24 rounded-full object-cover">
    @else
        <span class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-primary-50 text-primary-600 text-2xl font-bold">
            {{ \Illuminate\Support\Str::of($member->name)->substr(0, 1)->upper() }}
        </span>
    @endif

    <h3 class="mt-4 text-lg font-bold text-gray-900">{{ $member->name }}</h3>
    <p class="text-sm font-semibold text-primary-600">{{ $member->title }}</p>
    @if ($member->specialty)
        <p class="mt-1 text-xs text-gray-500">{{ $member->specialty }}</p>
    @endif

    @if ($member->biography)
        <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ \Illuminate\Support\Str::limit($member->biography, 160) }}</p>
    @endif
</article>
