@php
    $statusColors = ['pending' => 'amber', 'paid' => 'primary', 'failed' => 'red', 'refunded' => 'gray'];
@endphp

<x-layouts.admin title="Payment {{ $payment->reference }}">
    <div class="mb-6">
        <a href="{{ route('admin.payments.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
            &larr; Back to Payments
        </a>
    </div>

    <div class="max-w-2xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="font-mono text-xs text-gray-500">{{ $payment->reference }}</p>
                <h1 class="mt-1 text-xl font-bold text-gray-900">{{ number_format($payment->amount, 2) }}</h1>
            </div>
            <x-badge :color="$statusColors[$payment->status] ?? 'gray'">{{ $payment->statusLabel() }}</x-badge>
        </div>

        <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
            <div>
                <dt class="text-gray-500">Patient</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $payment->patient->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Service</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $payment->appointment->service->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Branch</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $payment->appointment->branch->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Method</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $payment->methodLabel() }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Reference / Transaction ID</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $payment->gateway_reference ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Recorded By</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $payment->recordedBy?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Paid At</dt>
                <dd class="mt-1 font-medium text-gray-900">{{ $payment->paid_at?->format('d M Y, g:i A') ?? 'Not yet paid' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Appointment</dt>
                <dd class="mt-1">
                    <a href="{{ route('admin.appointments.show', $payment->appointment) }}" class="font-mono text-xs text-primary-700 hover:text-primary-800">
                        {{ $payment->appointment->reference }}
                    </a>
                </dd>
            </div>
        </dl>

        @if ($payment->notes)
            <div class="mt-6">
                <dt class="text-sm text-gray-500">Notes</dt>
                <dd class="mt-1.5 rounded-lg bg-surface-50 p-4 text-sm text-gray-700">{{ $payment->notes }}</dd>
            </div>
        @endif

        <div class="mt-8 flex flex-wrap gap-3">
            @if ($payment->isPaid())
                <x-btn :href="route('admin.receipts.show', $payment)" variant="primary" target="_blank">
                    View Receipt
                </x-btn>
            @endif

            @if ($payment->status === 'pending')
                <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}" class="flex items-end gap-3">
                    @csrf
                    @method('PATCH')
                    <x-form.input label="Transaction ID (optional)" name="gateway_reference" />
                    <x-btn type="submit" variant="primary">Confirm Payment</x-btn>
                </form>
                <form method="POST" action="{{ route('admin.payments.fail', $payment) }}">
                    @csrf
                    @method('PATCH')
                    <x-btn type="submit" variant="secondary">Mark as Failed</x-btn>
                </form>
            @endif
        </div>
    </div>
</x-layouts.admin>
