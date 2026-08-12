<x-layouts.admin title="Inventory History">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Full log of every stock movement recorded.</p>
        <x-btn :href="route('admin.inventory.index')" variant="ghost" size="sm">
            &larr; Back to Stock Overview
        </x-btn>
    </div>

    <form method="GET" action="{{ route('admin.inventory.history') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <select name="medicine_id" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Products</option>
            @foreach ($medicines as $medicine)
                <option value="{{ $medicine->id }}" @selected((string) ($filters['medicine_id'] ?? '') === (string) $medicine->id)>{{ $medicine->name }}</option>
            @endforeach
        </select>
        <select name="type" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Types</option>
            <option value="in" @selected(($filters['type'] ?? '') === 'in')>Stock In</option>
            <option value="out" @selected(($filters['type'] ?? '') === 'out')>Stock Out</option>
        </select>
        <input
            type="date"
            name="date"
            value="{{ $filters['date'] ?? '' }}"
            onchange="this.form.submit()"
            class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm"
        >
    </form>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Type</th>
                        <th class="px-5 py-3">Quantity</th>
                        <th class="px-5 py-3">Reference</th>
                        <th class="px-5 py-3">By</th>
                        <th class="px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.medicines.show', $transaction->medicine) }}" class="font-medium text-gray-900 hover:text-primary-700">
                                    {{ $transaction->medicine->name }}
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <x-badge :color="$transaction->type === 'in' ? 'primary' : 'red'">{{ $transaction->typeLabel() }}</x-badge>
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $transaction->quantity }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $transaction->reference ?: '—' }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $transaction->user->name }}</td>
                            <td class="px-5 py-4 text-gray-500">{{ $transaction->created_at->format('d M Y, g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">No stock movements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactions->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
