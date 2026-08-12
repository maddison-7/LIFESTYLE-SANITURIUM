<x-layouts.admin title="Services">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Manage the services shown on the public Services page.</p>
        <x-btn :href="route('admin.services.create')" variant="primary" size="sm">
            Add Service
        </x-btn>
    </div>

    <form method="GET" action="{{ route('admin.services.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
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
    </form>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Service</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($services as $service)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($service->image)
                                        <img src="{{ $service->imageUrl() }}" alt="{{ $service->name }}" class="h-10 w-10 rounded-lg object-cover">
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $service->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $service->category }}</td>
                            <td class="px-5 py-4">
                                <x-badge :color="$service->status === 'active' ? 'primary' : 'gray'">{{ ucfirst($service->status) }}</x-badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        Edit
                                    </a>
                                    <x-confirm-form
                                        :action="route('admin.services.destroy', $service)"
                                        title="Delete this service?"
                                        :message="'This will permanently remove ' . $service->name . ' from the public Services page.'"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-500">No services found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($services->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
