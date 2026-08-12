@php
    $statusColors = [
        'pending' => 'amber',
        'confirmed' => 'primary',
        'rescheduled' => 'blue',
        'completed' => 'gray',
        'cancelled' => 'red',
    ];
    $isFinal = in_array($appointment->status, ['completed', 'cancelled']);
@endphp

<x-layouts.admin title="Appointment {{ $appointment->reference }}">
    <div class="mb-6">
        <a href="{{ route('admin.appointments.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
            &larr; Back to Appointments
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-mono text-xs text-gray-500">{{ $appointment->reference }}</p>
                        <h1 class="mt-1 text-xl font-bold text-gray-900">{{ $appointment->patient->name }}</h1>
                    </div>
                    <x-badge :color="$statusColors[$appointment->status] ?? 'gray'">{{ $appointment->statusLabel() }}</x-badge>
                </div>

                <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <dt class="text-gray-500">Phone</dt>
                        <dd class="mt-1 font-medium text-gray-900">
                            <a href="tel:{{ $appointment->patient->phone }}" class="hover:text-primary-700">{{ $appointment->patient->phone }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Email</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $appointment->patient->email ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Gender</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ ucfirst($appointment->patient->gender ?? '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Service</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $appointment->service->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Branch</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $appointment->branch->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Requested On</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $appointment->created_at->format('d M Y, g:i A') }}</dd>
                    </div>
                </dl>

                @if ($appointment->message)
                    <div class="mt-6">
                        <dt class="text-sm text-gray-500">Patient Message</dt>
                        <dd class="mt-1.5 rounded-lg bg-surface-50 p-4 text-sm text-gray-700">{{ $appointment->message }}</dd>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
                <h2 class="text-base font-bold text-gray-900">Reschedule & Notes</h2>
                <p class="mt-1 text-sm text-gray-500">Updating the date or time automatically sets the status to Rescheduled.</p>

                <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="mt-5 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-form.input label="Appointment Date" name="appointment_date" type="date" :value="$appointment->appointment_date->toDateString()" :required="true" />
                        <x-form.input label="Appointment Time" name="appointment_time" type="time" :value="\Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('H:i')" :required="true" />
                    </div>
                    <x-form.textarea label="Admin Notes" name="admin_notes" :value="$appointment->admin_notes" hint="Internal notes — not visible to the patient." />

                    <x-btn type="submit" variant="primary">Save Changes</x-btn>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-surface-200 bg-white p-6">
                <h2 class="text-base font-bold text-gray-900">Status Actions</h2>

                @if ($isFinal)
                    <p class="mt-3 text-sm text-gray-500">This appointment is finalized as <strong>{{ $appointment->statusLabel() }}</strong>.</p>
                @else
                    <div class="mt-4 space-y-3">
                        @if (in_array($appointment->status, ['pending', 'rescheduled']))
                            <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="confirmed">
                                <x-btn type="submit" variant="primary" class="w-full">Confirm Appointment</x-btn>
                            </form>
                        @endif

                        @if ($appointment->status === 'confirmed')
                            <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <x-btn type="submit" variant="secondary" class="w-full">Mark as Completed</x-btn>
                            </form>
                        @endif

                        <x-confirm-form
                            :action="route('admin.appointments.updateStatus', $appointment)"
                            method="PATCH"
                            title="Cancel this appointment?"
                            :message="'This will mark the appointment for ' . $appointment->patient->name . ' as cancelled.'"
                            confirm-label="Cancel Appointment"
                            trigger="Cancel Appointment"
                            class="block w-full rounded-lg border border-red-200 px-4 py-2.5 text-center text-sm font-semibold text-red-600 hover:bg-red-50 transition-base"
                        >
                            <input type="hidden" name="status" value="cancelled">
                        </x-confirm-form>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-surface-200 bg-white p-6">
                <h2 class="text-base font-bold text-gray-900">Payments</h2>

                @if ($appointment->payments->isNotEmpty())
                    <ul class="mt-4 space-y-3">
                        @foreach ($appointment->payments as $payment)
                            <li class="flex items-center justify-between rounded-lg bg-surface-50 px-3.5 py-2.5 text-sm">
                                <div>
                                    <p class="font-medium text-gray-900">{{ number_format($payment->amount, 2) }}</p>
                                    <p class="text-xs text-gray-500">{{ $payment->methodLabel() }}</p>
                                </div>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="text-xs font-semibold text-primary-700 hover:text-primary-800">
                                    {{ ucfirst($payment->status) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('admin.appointments.payments.store', $appointment) }}" class="mt-4 space-y-3">
                    @csrf
                    <x-form.input label="Amount" name="amount" type="number" step="0.01" min="0.01" :required="true" />
                    <x-form.select
                        label="Method"
                        name="method"
                        :options="['cash' => 'Cash', 'mobile_money' => 'Mobile Money', 'card' => 'Card', 'bank_transfer' => 'Bank Transfer']"
                        :required="true"
                    />
                    <x-form.input label="Transaction ID" name="gateway_reference" hint="Optional" />
                    <x-btn type="submit" variant="primary" class="w-full">Record Payment</x-btn>
                </form>
            </div>

            <div class="rounded-2xl border border-surface-200 bg-white p-6">
                <h2 class="text-base font-bold text-gray-900">Contact Patient</h2>
                <div class="mt-4 space-y-3">
                    <x-btn
                        :href="'https://wa.me/' . preg_replace('/\D+/', '', $appointment->patient->phone)"
                        variant="whatsapp"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-full"
                    >
                        Chat on WhatsApp
                    </x-btn>
                    <x-btn :href="'tel:' . $appointment->patient->phone" variant="secondary" class="w-full">
                        Call Patient
                    </x-btn>
                </div>
            </div>

            <div class="rounded-2xl border border-surface-200 bg-white p-6">
                <h2 class="text-base font-bold text-gray-900">Reminders</h2>
                <p class="mt-1 text-xs text-gray-500">Sent automatically the day before a confirmed appointment. You can also send one now.</p>

                <form method="POST" action="{{ route('admin.appointments.remind', $appointment) }}" class="mt-4">
                    @csrf
                    <x-btn type="submit" variant="secondary" class="w-full">Send Reminder Now</x-btn>
                </form>

                @if ($appointment->reminders->isNotEmpty())
                    <ul class="mt-4 space-y-2.5">
                        @foreach ($appointment->reminders as $reminder)
                            <li class="flex items-center justify-between text-xs">
                                <span class="text-gray-600">{{ $reminder->channelLabel() }} &middot; {{ $reminder->created_at->format('d M Y, g:i A') }}</span>
                                <x-badge :color="$reminder->status === 'sent' ? 'primary' : 'red'">{{ ucfirst($reminder->status) }}</x-badge>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
