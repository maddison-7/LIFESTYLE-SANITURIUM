@php
    $statusColors = ['pending' => 'amber', 'confirmed' => 'primary', 'rescheduled' => 'blue', 'completed' => 'gray', 'cancelled' => 'red'];
@endphp

<x-layouts.portal title="My Appointments">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('My Appointments') }}</h1>
        <x-btn :href="route('appointments.create')" variant="primary" size="sm">{{ __('Book New') }}</x-btn>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="divide-y divide-surface-100">
            @forelse ($appointments as $appointment)
                <a href="{{ route('portal.appointments.show', $appointment) }}" class="flex items-center justify-between px-6 py-4 hover:bg-surface-50">
                    <div>
                        <p class="font-medium text-gray-900">{{ $appointment->service->name }}</p>
                        <p class="text-xs text-gray-500">{{ $appointment->branch->name }} &middot; {{ $appointment->appointment_date->format('d M Y') }} &middot; {{ \Illuminate\Support\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                    </div>
                    <x-badge :color="$statusColors[$appointment->status] ?? 'gray'">{{ __($appointment->statusLabel()) }}</x-badge>
                </a>
            @empty
                <p class="px-6 py-12 text-center text-gray-500 text-sm">{{ __("You haven't booked any appointments yet.") }}</p>
            @endforelse
        </div>

        @if ($appointments->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</x-layouts.portal>
