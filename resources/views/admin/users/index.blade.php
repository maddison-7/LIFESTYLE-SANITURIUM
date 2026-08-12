@php
    $roleLabels = [
        'super_admin' => 'Super Admin',
        'clinic_admin' => 'Clinic Admin',
        'receptionist' => 'Receptionist',
        'healthcare_staff' => 'Healthcare Staff',
    ];
    $roleColors = [
        'super_admin' => 'primary',
        'clinic_admin' => 'blue',
        'receptionist' => 'amber',
        'healthcare_staff' => 'gray',
    ];
@endphp

<x-layouts.admin title="Users">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Manage admin and staff accounts with access to this dashboard.</p>
        <x-btn :href="route('admin.users.create')" variant="primary" size="sm">
            Add User
        </x-btn>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="sm:col-span-2">
            <input
                type="text"
                name="search"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="Search by name or email..."
                class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
        </div>
        <select name="role" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Roles</option>
            @foreach ($roleLabels as $value => $label)
                <option value="{{ $value }}" @selected(($filters['role'] ?? '') === $value)>{{ $label }}</option>
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
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Joined</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                <x-badge :color="$roleColors[$user->role] ?? 'gray'">{{ $roleLabels[$user->role] ?? $user->role }}</x-badge>
                            </td>
                            <td class="px-5 py-4">
                                <x-badge :color="$user->isActive() ? 'primary' : 'red'">{{ ucfirst($user->status) }}</x-badge>
                            </td>
                            <td class="px-5 py-4 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        Edit
                                    </a>
                                    @if (!$user->is(auth()->user()))
                                        <x-confirm-form
                                            :action="route('admin.users.destroy', $user)"
                                            title="Delete this user?"
                                            :message="'This will permanently remove ' . $user->name . '\'s access to the admin dashboard.'"
                                        />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
