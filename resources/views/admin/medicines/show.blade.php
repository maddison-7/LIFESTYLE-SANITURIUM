<x-layouts.admin title="{{ $medicine->name }}">
    <div class="mb-6">
        <a href="{{ route('admin.medicines.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
            &larr; Back to Medicines
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $medicine->name }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ $medicine->category }}</p>
                </div>
                <x-badge :color="$medicine->status === 'active' ? 'primary' : 'gray'">{{ $medicine->statusLabel() }}</x-badge>
            </div>

            <div class="mt-6 rounded-xl bg-surface-50 p-4 text-center">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Current Stock</p>
                <p class="mt-1 text-3xl font-bold {{ $medicine->isLowStock() ? 'text-red-600' : 'text-gray-900' }}">{{ $medicine->quantity }}</p>
                @if ($medicine->isLowStock())
                    <p class="mt-1 text-xs font-semibold text-red-600">Low stock</p>
                @endif
            </div>

            <dl class="mt-6 space-y-4 text-sm">
                <div>
                    <dt class="text-gray-500">Unit Price</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $medicine->unit_price !== null ? number_format($medicine->unit_price, 2) : 'Not set' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Expiry Date</dt>
                    <dd class="mt-1 font-medium {{ $medicine->isExpired() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $medicine->expiry_date?->format('d M Y') ?? 'Not set' }}
                        @if ($medicine->isExpired()) (Expired) @endif
                    </dd>
                </div>
                @if ($medicine->description)
                    <div>
                        <dt class="text-gray-500">Description</dt>
                        <dd class="mt-1 text-gray-700">{{ $medicine->description }}</dd>
                    </div>
                @endif
            </dl>

            <div class="mt-6 flex flex-col gap-3">
                <x-btn :href="route('admin.medicines.edit', $medicine)" variant="secondary">Edit Details</x-btn>

                @if ($medicine->transactions->isEmpty())
                    <x-confirm-form
                        :action="route('admin.medicines.destroy', $medicine)"
                        title="Delete this medicine?"
                        :message="'This will permanently remove ' . $medicine->name . ' from the catalogue.'"
                        class="block w-full rounded-lg border border-red-200 px-4 py-2.5 text-center text-sm font-semibold text-red-600 hover:bg-red-50 transition-base"
                    />
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="flex flex-wrap gap-3">
                <x-admin.stock-movement-modal :medicine="$medicine" type="in" trigger="Stock In" />
                <x-admin.stock-movement-modal :medicine="$medicine" type="out" trigger="Stock Out" />
            </div>

            <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-100">
                    <h2 class="text-base font-bold text-gray-900">Stock Movement History</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Type</th>
                                <th class="px-5 py-3">Quantity</th>
                                <th class="px-5 py-3">Reference</th>
                                <th class="px-5 py-3">By</th>
                                <th class="px-5 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            @forelse ($medicine->transactions as $transaction)
                                <tr>
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
                                    <td colspan="5" class="px-5 py-12 text-center text-gray-500">No stock movement recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
