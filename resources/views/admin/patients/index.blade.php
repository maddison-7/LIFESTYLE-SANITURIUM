@php
    $genderLabels = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
@endphp

<x-layouts.admin title="Patients / Clients">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Patient records are created automatically from appointment requests, or added here directly.</p>
        <x-btn :href="route('admin.patients.create')" variant="primary" size="sm">
            Add Patient
        </x-btn>
    </div>

    <form method="GET" action="{{ route('admin.patients.index') }}" class="mb-6">
        <input
            type="text"
            name="search"
            value="{{ $filters['search'] ?? '' }}"
            placeholder="Search by name, phone or email..."
            class="w-full sm:max-w-sm rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
    </form>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Phone</th>
                        <th class="px-5 py-3">Gender</th>
                        <th class="px-5 py-3">Registered</th>
                        <th class="px-5 py-3">Appointments</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($patients as $patient)
                        <tr>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.patients.show', $patient) }}" class="font-medium text-gray-900 hover:text-primary-700">
                                    {{ $patient->name }}
                                </a>
                                @if ($patient->email)
                                    <p class="text-xs text-gray-500">{{ $patient->email }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $patient->phone }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $genderLabels[$patient->gender] ?? '—' }}</td>
                            <td class="px-5 py-4 text-gray-500">{{ $patient->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <x-badge color="primary">{{ $patient->appointments_count }}</x-badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.patients.show', $patient) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        View
                                    </a>
                                    <a href="{{ route('admin.patients.edit', $patient) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">No patients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($patients->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
