<x-layouts.admin title="Appointment Reports">
    @include('admin.reports._tabs')
    @include('admin.reports._date_filter', ['route' => 'admin.reports.appointments'])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <p class="text-sm font-medium text-gray-500">Total Appointments</p>
            <p class="mt-2 text-4xl font-bold text-gray-900">{{ $total }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $from->format('d M Y') }} &ndash; {{ $to->format('d M Y') }}</p>
        </div>

        <div class="lg:col-span-2 rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">By Status</h2>
            <x-admin.bar-list :items="$statusBreakdown" />
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-surface-200 bg-white p-6">
        <h2 class="text-sm font-bold text-gray-900 mb-4">Appointments Over Time</h2>
        <x-admin.bar-list :items="$dailyBreakdown" empty-message="No appointments in this date range." />
    </div>
</x-layouts.admin>
