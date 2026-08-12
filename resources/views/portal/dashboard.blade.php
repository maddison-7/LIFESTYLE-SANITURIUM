@php
    $statusColors = ['pending' => 'amber', 'confirmed' => 'primary', 'rescheduled' => 'blue', 'completed' => 'gray', 'cancelled' => 'red'];
@endphp

<x-layouts.portal title="Dashboard">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Welcome back, :name', ['name' => $patient->name]) }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __("Here's an overview of your care with Lifestyle Sanitarium Clinic.") }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl border border-surface-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">{{ __('Total Appointments') }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalAppointments }}</p>
        </div>
        <div class="rounded-2xl border border-surface-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">{{ __('Upcoming') }}</p>
            <p class="mt-2 text-3xl font-bold text-primary-700">{{ $upcoming->count() }}</p>
        </div>
        <a href="{{ route('appointments.create') }}" class="rounded-2xl border-2 border-dashed border-primary-200 bg-primary-50 p-5 flex items-center justify-center text-center hover:bg-primary-100 transition-base">
            <span class="text-sm font-semibold text-primary-700">+ {{ __('Book New Appointment') }}</span>
        </a>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden mb-8">
        <div class="flex items-center justify-between px-6 py-4 border-b border-surface-100">
            <h2 class="text-base font-bold text-gray-900">{{ __('Upcoming Appointments') }}</h2>
            <a href="{{ route('portal.appointments.index') }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">{{ __('View All') }}</a>
        </div>

        @if ($upcoming->isEmpty())
            <p class="px-6 py-10 text-center text-gray-500 text-sm">{{ __('No upcoming appointments. Ready to book one?') }}</p>
        @else
            <div class="divide-y divide-surface-100">
                @foreach ($upcoming as $appointment)
                    <a href="{{ route('portal.appointments.show', $appointment) }}" class="flex items-center justify-between px-6 py-4 hover:bg-surface-50">
                        <div>
                            <p class="font-medium text-gray-900">{{ $appointment->service->name }}</p>
                            <p class="text-xs text-gray-500">{{ $appointment->branch->name }} &middot; {{ $appointment->appointment_date->format('d M Y') }}</p>
                        </div>
                        <x-badge :color="$statusColors[$appointment->status] ?? 'gray'">{{ __($appointment->statusLabel()) }}</x-badge>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    @if ($recentPayments->isNotEmpty())
        <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-100">
                <h2 class="text-base font-bold text-gray-900">{{ __('Recent Payments') }}</h2>
            </div>
            <div class="divide-y divide-surface-100">
                @foreach ($recentPayments as $payment)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ number_format($payment->amount, 2) }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->appointment->service->name }} &middot; {{ $payment->created_at->format('d M Y') }}</p>
                        </div>
                        @if ($payment->isPaid())
                            <x-btn :href="route('portal.receipts.show', $payment)" variant="ghost" size="sm" target="_blank">{{ __('View Receipt') }}</x-btn>
                        @else
                            <x-badge color="amber">{{ __($payment->statusLabel()) }}</x-badge>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-layouts.portal>
