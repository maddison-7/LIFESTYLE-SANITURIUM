@php
    $movementItems = [
        ['label' => 'Stock In', 'value' => $stockIn, 'color' => 'bg-primary-600'],
        ['label' => 'Stock Out', 'value' => $stockOut, 'color' => 'bg-red-500'],
    ];
@endphp

<x-layouts.admin title="Inventory Reports">
    @include('admin.reports._tabs')
    @include('admin.reports._date_filter', ['route' => 'admin.reports.inventory', 'showExport' => false])

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-gray-900">Stock Movement</h2>
                <a href="{{ route('admin.reports.inventory', request()->only(['from', 'to']) + ['export' => 'movement']) }}" class="text-xs font-semibold text-primary-700 hover:text-primary-800">
                    Export CSV
                </a>
            </div>
            <p class="text-xs text-gray-500 mb-4">{{ $from->format('d M Y') }} &ndash; {{ $to->format('d M Y') }}</p>
            <x-admin.bar-list :items="$movementItems" empty-message="No stock movement in this date range." />
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">Low Stock Items</h2>
            @if ($lowStock->isEmpty())
                <p class="text-sm text-gray-500 text-center py-8">No low-stock items right now.</p>
            @else
                <ul class="divide-y divide-surface-100">
                    @foreach ($lowStock as $medicine)
                        <li class="flex items-center justify-between py-2.5 text-sm">
                            <a href="{{ route('admin.medicines.show', $medicine) }}" class="text-gray-700 hover:text-primary-700">{{ $medicine->name }}</a>
                            <x-badge color="red">{{ $medicine->quantity }} units</x-badge>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-surface-100">
            <h2 class="text-sm font-bold text-gray-900">Current Stock Levels</h2>
            <a href="{{ route('admin.reports.inventory', ['export' => 'stock']) }}" class="text-xs font-semibold text-primary-700 hover:text-primary-800">
                Export CSV
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Stock</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($medicines as $medicine)
                        <tr>
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.medicines.show', $medicine) }}" class="font-medium text-gray-900 hover:text-primary-700">{{ $medicine->name }}</a>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $medicine->category }}</td>
                            <td class="px-5 py-3">
                                <x-badge :color="$medicine->isLowStock() ? 'red' : 'primary'">{{ $medicine->quantity }}</x-badge>
                            </td>
                            <td class="px-5 py-3">
                                <x-badge :color="$medicine->status === 'active' ? 'primary' : 'gray'">{{ $medicine->statusLabel() }}</x-badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-500">No medicines in the catalogue yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
