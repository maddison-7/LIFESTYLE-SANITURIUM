<x-layouts.admin title="Healthcare Team">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Manage healthcare team profiles shown on the public Healthcare Team page.</p>
        <x-btn :href="route('admin.team.create')" variant="primary" size="sm">
            Add Team Member
        </x-btn>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Title</th>
                        <th class="px-5 py-3">Specialty</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($staff as $member)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($member->photo)
                                        <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $member->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $member->title }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $member->specialty ?: '—' }}</td>
                            <td class="px-5 py-4">
                                <x-badge :color="$member->status === 'active' ? 'primary' : 'gray'">{{ ucfirst($member->status) }}</x-badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.team.edit', $member) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        Edit
                                    </a>
                                    <x-confirm-form
                                        :action="route('admin.team.destroy', $member)"
                                        title="Remove this team member?"
                                        :message="'This will permanently remove ' . $member->name . ' from the public Healthcare Team page.'"
                                        confirm-label="Remove"
                                        trigger="Remove"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">No team members added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($staff->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $staff->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
