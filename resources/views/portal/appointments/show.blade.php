@php
    $statusColors = ['pending' => 'amber', 'confirmed' => 'primary', 'rescheduled' => 'blue', 'completed' => 'gray', 'cancelled' => 'red'];
    $hasPaidPayment = $appointment->payments->contains(fn ($p) => $p->isPaid());
    $hasPendingPayment = $appointment->payments->contains(fn ($p) => $p->status === 'pending');
@endphp

<x-layouts.portal title="Appointment {{ $appointment->reference }}">
    <div class="mb-6">
        <a href="{{ route('portal.appointments.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
            &larr; {{ __('Back to My Appointments') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="font-mono text-xs text-gray-500">{{ $appointment->reference }}</p>
                    <h1 class="mt-1 text-xl font-bold text-gray-900">{{ $appointment->service->name }}</h1>
                </div>
                <x-badge :color="$statusColors[$appointment->status] ?? 'gray'">{{ __($appointment->statusLabel()) }}</x-badge>
            </div>

            <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                <div>
                    <dt class="text-gray-500">{{ __('Branch') }}</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $appointment->branch->name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('Date & Time') }}</dt>
                    <dd class="mt-1 font-medium text-gray-900">
                        {{ $appointment->appointment_date->format('d M Y') }} {{ __('at') }} {{ \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('Requested On') }}</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $appointment->created_at->format('d M Y') }}</dd>
                </div>
            </dl>

            @if ($appointment->message)
                <div class="mt-6">
                    <dt class="text-sm text-gray-500">{{ __('Your Message') }}</dt>
                    <dd class="mt-1.5 rounded-lg bg-surface-50 p-4 text-sm text-gray-700">{{ $appointment->message }}</dd>
                </div>
            @endif

            <div class="mt-6 rounded-lg bg-primary-50 border border-primary-100 p-4">
                <p class="text-sm text-primary-800">
                    {{ __('Need to make changes? Please contact us directly — reschedules and cancellations are handled by our team to make sure nothing is missed.') }}
                </p>
                <div class="mt-3">
                    <x-btn :href="whatsapp_link('Hello, I would like to ask about my appointment ' . $appointment->reference . '.')" variant="whatsapp" size="sm" target="_blank" rel="noopener noreferrer">
                        {{ __('Chat on WhatsApp') }}
                    </x-btn>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-base font-bold text-gray-900">{{ __('Payment') }}</h2>

            @if ($hasPaidPayment)
                @php $paidPayment = $appointment->payments->first(fn ($p) => $p->isPaid()); @endphp
                <p class="mt-3 text-sm text-gray-600">{{ __('Paid in full.') }}</p>
                <div class="mt-4">
                    <x-btn :href="route('portal.receipts.show', $paidPayment)" variant="primary" class="w-full" target="_blank">
                        {{ __('View Receipt') }}
                    </x-btn>
                </div>
            @elseif ($hasPendingPayment)
                <p class="mt-3 text-sm text-amber-700">{{ __('Your payment has been submitted and is awaiting confirmation from our team.') }}</p>
            @else
                <p class="mt-3 text-sm text-gray-600">
                    {{ __("If you've sent payment via mobile money, let us know the details below and we'll confirm it.") }}
                </p>
                <form method="POST" action="{{ route('portal.appointments.payments.store', $appointment) }}" class="mt-4 space-y-3">
                    @csrf
                    <x-form.input :label="__('Amount Paid')" name="amount" type="number" step="0.01" min="0.01" :required="true" />
                    <x-form.select
                        :label="__('Method')"
                        name="method"
                        :options="['mobile_money' => __('Mobile Money'), 'bank_transfer' => __('Bank Transfer'), 'cash' => __('Cash'), 'card' => __('Card')]"
                        :required="true"
                    />
                    <x-form.input :label="__('Transaction ID')" name="gateway_reference" :hint="__('From your mobile money confirmation SMS.')" />
                    <x-btn type="submit" variant="primary" class="w-full">{{ __('Submit Payment') }}</x-btn>
                </form>
            @endif
        </div>
    </div>
</x-layouts.portal>
