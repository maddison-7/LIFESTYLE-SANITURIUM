@php
    $medicineOptions = $medicines->pluck('name', 'id')->all();
    $typeOptions = ['in' => 'Stock In', 'out' => 'Stock Out'];
@endphp

<x-layouts.admin title="Inventory">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Current stock levels across all medicines and health products.</p>
        <div class="flex gap-3">
            <x-btn :href="route('admin.inventory.history')" variant="secondary" size="sm">
                Transaction History
            </x-btn>
            <x-btn :href="route('admin.medicines.index')" variant="ghost" size="sm">
                Manage Medicines
            </x-btn>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl border border-surface-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">Total Products</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $medicines->count() }}</p>
        </div>
        <div class="rounded-2xl border border-surface-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">Low Stock Items</p>
            <p class="mt-2 text-3xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $lowStockCount }}</p>
        </div>
        <div class="rounded-2xl border border-surface-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">Out of Stock</p>
            <p class="mt-2 text-3xl font-bold {{ $outOfStockCount > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $outOfStockCount }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8 mb-8">
        <h2 class="text-base font-bold text-gray-900">Record Stock Movement</h2>
        <p class="mt-1 text-sm text-gray-500">Log a new delivery, correction, or usage against any product.</p>

        <form method="POST" action="{{ route('admin.inventory.store') }}" class="mt-5 grid grid-cols-1 sm:grid-cols-4 gap-4 items-start">
            @csrf
            <x-form.select label="Product" name="medicine_id" :options="$medicineOptions" placeholder="Select a product" :required="true" />
            <x-form.select label="Type" name="type" :options="$typeOptions" :required="true" />
            <x-form.input label="Quantity" name="quantity" type="number" min="1" :required="true" />
            <x-form.input label="Reference / Notes" name="reference" hint="Optional" />

            <div class="sm:col-span-4">
                <x-btn type="submit" variant="primary">Record Movement</x-btn>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="px-6 py-4 border-b border-surface-100">
            <h2 class="text-base font-bold text-gray-900">Current Stock</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Stock</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($medicines as $medicine)
                        <tr>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.medicines.show', $medicine) }}" class="font-medium text-gray-900 hover:text-primary-700">
                                    {{ $medicine->name }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $medicine->category }}</td>
                            <td class="px-5 py-4">
                                <x-badge :color="$medicine->isLowStock() ? 'red' : 'primary'">{{ $medicine->quantity }} units</x-badge>
                            </td>
                            <td class="px-5 py-4">
                                <x-badge :color="$medicine->status === 'active' ? 'primary' : 'gray'">{{ $medicine->statusLabel() }}</x-badge>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.medicines.show', $medicine) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">No medicines in the catalogue yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
