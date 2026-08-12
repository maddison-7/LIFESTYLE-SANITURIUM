<x-layouts.admin title="Medicines">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Manage the medicines and health products catalogue.</p>
        <div class="flex gap-3">
            <x-btn :href="route('admin.inventory.index')" variant="secondary" size="sm">
                View Stock Levels
            </x-btn>
            <x-btn :href="route('admin.medicines.create')" variant="primary" size="sm">
                Add Medicine
            </x-btn>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.medicines.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-4 gap-4 items-center">
        <input
            type="text"
            name="search"
            value="{{ $filters['search'] ?? '' }}"
            placeholder="Search by name..."
            class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
        <select name="category" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Statuses</option>
            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
            <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
        </select>
        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="low_stock" value="1" onchange="this.form.submit()" @checked($filters['low_stock'] ?? false) class="rounded border-surface-300 text-primary-600 focus:ring-primary-500">
            Low stock only
        </label>
    </form>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Product</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Stock</th>
                        <th class="px-5 py-3">Unit Price</th>
                        <th class="px-5 py-3">Expiry</th>
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
                            <td class="px-5 py-4 text-gray-600">{{ $medicine->unit_price !== null ? number_format($medicine->unit_price, 2) : '—' }}</td>
                            <td class="px-5 py-4">
                                @if ($medicine->expiry_date)
                                    <span class="{{ $medicine->isExpired() ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                        {{ $medicine->expiry_date->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <x-badge :color="$medicine->status === 'active' ? 'primary' : 'gray'">{{ $medicine->statusLabel() }}</x-badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.medicines.show', $medicine) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        View
                                    </a>
                                    <a href="{{ route('admin.medicines.edit', $medicine) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-500">No medicines found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($medicines->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $medicines->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
