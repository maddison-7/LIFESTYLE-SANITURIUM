@props([
    'medicine',
    'type',
    'trigger',
])

@php
    $isIn = $type === 'in';
@endphp

<div x-data="{ open: false }" class="inline-block">
    <button
        type="button"
        @click="open = true"
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-lg px-5 py-3 text-sm font-semibold transition-base ' . ($isIn ? 'bg-primary-600 text-white hover:bg-primary-700' : 'bg-white text-red-600 border border-red-200 hover:bg-red-50')]) }}
    >
        {{ $trigger }}
    </button>

    <div x-cloak x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-black/50"></div>
        <div x-show="open" x-transition class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <h3 class="text-base font-bold text-gray-900">{{ $isIn ? 'Record Stock In' : 'Record Stock Out' }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ $medicine->name }} &mdash; currently {{ $medicine->quantity }} units in stock.</p>

            <form method="POST" action="{{ route('admin.inventory.store') }}" class="mt-5 space-y-4">
                @csrf
                <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
                <input type="hidden" name="type" value="{{ $type }}">

                <x-form.input label="Quantity" name="quantity" type="number" min="1" :required="true" />
                <x-form.input label="Reference / Notes" name="reference" hint="e.g. supplier invoice, reason for use." />

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition-base {{ $isIn ? 'bg-primary-600 hover:bg-primary-700' : 'bg-red-600 hover:bg-red-700' }}"
                    >
                        {{ $isIn ? 'Add Stock' : 'Remove Stock' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
