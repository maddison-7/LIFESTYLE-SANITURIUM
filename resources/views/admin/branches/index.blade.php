@php
    $statusColors = ['open' => 'primary', 'coming_soon' => 'amber', 'closed' => 'gray'];
@endphp

<x-layouts.admin title="Branches">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Manage clinic branches shown on the public Branches page.</p>
        <x-btn :href="route('admin.branches.create')" variant="primary" size="sm">
            Add Branch
        </x-btn>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Branch</th>
                        <th class="px-5 py-3">Location</th>
                        <th class="px-5 py-3">Phone</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($branches as $branch)
                        <tr>
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $branch->name }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $branch->location }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $branch->phone ?: '—' }}</td>
                            <td class="px-5 py-4">
                                <x-badge :color="$statusColors[$branch->status] ?? 'gray'">{{ $branch->statusLabel() }}</x-badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.branches.edit', $branch) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        Edit
                                    </a>
                                    <x-confirm-form
                                        :action="route('admin.branches.destroy', $branch)"
                                        title="Delete this branch?"
                                        :message="'This will permanently remove ' . $branch->name . ' from the public Branches page.'"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">No branches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($branches->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $branches->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
