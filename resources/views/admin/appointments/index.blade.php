@php
    $statusColors = [
        'pending' => 'amber',
        'confirmed' => 'primary',
        'rescheduled' => 'blue',
        'completed' => 'gray',
        'cancelled' => 'red',
    ];
@endphp

<x-layouts.admin title="Appointments">
    <p class="text-sm text-gray-500 mb-2">Review, confirm, reschedule, cancel or complete appointment requests.</p>
    @if ($branchScoped)
        <p class="text-xs font-semibold text-primary-700 mb-6">Showing {{ $branches->firstWhere('id', auth()->user()->branch_id)?->name }} only.</p>
    @else
        <div class="mb-6"></div>
    @endif

    <form method="GET" action="{{ route('admin.appointments.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $branchScoped ? 4 : 5 }} gap-4">
        <div class="sm:col-span-2 lg:col-span-1">
            <input
                type="text"
                name="search"
                value="{{ $filters['search'] ?? '' }}"
                placeholder="Search name, phone, reference..."
                class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
            >
        </div>
        <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Statuses</option>
            @foreach (\App\Models\Appointment::STATUSES as $status)
                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @unless ($branchScoped)
            <select name="branch_id" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
                <option value="">All Branches</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" @selected((string) ($filters['branch_id'] ?? '') === (string) $branch->id)>{{ $branch->name }}</option>
                @endforeach
            </select>
        @endunless
        <select name="service_id" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Services</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}" @selected((string) ($filters['service_id'] ?? '') === (string) $service->id)>{{ $service->name }}</option>
            @endforeach
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
                        <th class="px-5 py-3">Reference</th>
                        <th class="px-5 py-3">Patient</th>
                        <th class="px-5 py-3">Service</th>
                        <th class="px-5 py-3">Branch</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Time</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td class="px-5 py-4 font-mono text-xs text-gray-600">{{ $appointment->reference }}</td>
                            <td class="px-5 py-4">
                                <p class="font-medium text-gray-900">{{ $appointment->patient->name }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->patient->phone }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $appointment->service->name }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $appointment->branch->name }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $appointment->appointment_date->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</td>
                            <td class="px-5 py-4">
                                <x-badge :color="$statusColors[$appointment->status] ?? 'gray'">{{ $appointment->statusLabel() }}</x-badge>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.appointments.show', $appointment) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-500">No appointment requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($appointments->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
