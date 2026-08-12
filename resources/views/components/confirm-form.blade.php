@props([
    'action',
    'method' => 'DELETE',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmLabel' => 'Delete',
    'trigger' => 'Delete',
])

<div x-data="{ open: false }" class="inline-block">
    <button type="button" @click="open = true" {{ $attributes->merge(['class' => 'text-sm font-semibold text-red-600 hover:text-red-700']) }}>
        {{ $trigger }}
    </button>

    <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-black/50"></div>
        <div x-show="open" x-transition class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-base font-bold text-gray-900">{{ $title }}</h3>
            <p class="mt-2 text-sm text-gray-600">{{ $message }}</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" @click="open = false" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900">
                    Cancel
                </button>
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @if (strtoupper($method) !== 'POST')
                        @method($method)
                    @endif
                    {{ $slot }}
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-sm font-semibold text-white hover:bg-red-700 transition-base">
                        {{ $confirmLabel }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
