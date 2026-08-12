@php
    $genderLabels = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
    $statusColors = [
        'pending' => 'amber',
        'confirmed' => 'primary',
        'rescheduled' => 'blue',
        'completed' => 'gray',
        'cancelled' => 'red',
    ];
@endphp

<x-layouts.admin title="Patient Profile">
    <div class="mb-6">
        <a href="{{ route('admin.patients.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
            &larr; Back to Patients
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $patient->name }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Registered {{ $patient->created_at->format('d M Y') }}</p>
                </div>
                <x-btn :href="route('admin.patients.edit', $patient)" variant="secondary" size="sm">Edit</x-btn>
            </div>

            <dl class="mt-6 space-y-4 text-sm">
                <div>
                    <dt class="text-gray-500">Phone</dt>
                    <dd class="mt-1 font-medium text-gray-900">
                        <a href="tel:{{ $patient->phone }}" class="hover:text-primary-700">{{ $patient->phone }}</a>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $patient->email ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Gender</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $genderLabels[$patient->gender] ?? '—' }}</dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-col gap-3">
                <x-btn
                    :href="'https://wa.me/' . preg_replace('/\D+/', '', $patient->phone)"
                    variant="whatsapp"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    Chat on WhatsApp
                </x-btn>

                @if ($patient->appointments->isEmpty())
                    <x-confirm-form
                        :action="route('admin.patients.destroy', $patient)"
                        title="Delete this patient?"
                        :message="'This will permanently remove ' . $patient->name . ' from the system.'"
                        class="block w-full rounded-lg border border-red-200 px-4 py-2.5 text-center text-sm font-semibold text-red-600 hover:bg-red-50 transition-base"
                    />
                @endif
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-100">
                    <h2 class="text-base font-bold text-gray-900">Appointment History</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Reference</th>
                                <th class="px-5 py-3">Service</th>
                                <th class="px-5 py-3">Branch</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            @forelse ($patient->appointments as $appointment)
                                <tr>
                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.appointments.show', $appointment) }}" class="font-mono text-xs text-primary-700 hover:text-primary-800">
                                            {{ $appointment->reference }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-4 text-gray-600">{{ $appointment->service->name }}</td>
                                    <td class="px-5 py-4 text-gray-600">{{ $appointment->branch->name }}</td>
                                    <td class="px-5 py-4 text-gray-600">{{ $appointment->appointment_date->format('d M Y') }}</td>
                                    <td class="px-5 py-4">
                                        <x-badge :color="$statusColors[$appointment->status] ?? 'gray'">{{ $appointment->statusLabel() }}</x-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-gray-500">No appointments yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
