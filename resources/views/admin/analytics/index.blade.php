<x-layouts.admin title="Analytics">
    <p class="text-sm text-gray-500 mb-6">Trends over the last {{ $monthsCovered }} months. For exportable, date-ranged breakdowns see Reports.</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">Completion Rate</p>
            <p class="mt-2 text-3xl font-bold text-primary-700">{{ $funnel['completionRate'] }}%</p>
            <p class="mt-1 text-xs text-gray-400">of requested appointments were completed</p>
        </div>
        <div class="rounded-2xl border border-surface-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">Cancellation Rate</p>
            <p class="mt-2 text-3xl font-bold {{ $funnel['cancellationRate'] > 20 ? 'text-red-600' : 'text-gray-900' }}">{{ $funnel['cancellationRate'] }}%</p>
            <p class="mt-1 text-xs text-gray-400">of requested appointments were cancelled</p>
        </div>
        <div class="rounded-2xl border border-surface-200 bg-white p-5">
            <p class="text-sm font-medium text-gray-500">Total Requested</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $funnel['stages'][0]['value'] }}</p>
            <p class="mt-1 text-xs text-gray-400">last {{ $monthsCovered }} months</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">Appointment Funnel</h2>
            <x-admin.bar-list :items="$funnel['stages']" />
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">Monthly Appointment Volume</h2>
            <x-admin.bar-list :items="$monthlyAppointments" empty-message="No appointments in this period." />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">Monthly Revenue</h2>
            <x-admin.bar-list :items="$monthlyRevenue" empty-message="No payments in this period." />
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">Top Branches</h2>
            <x-admin.bar-list :items="$topBranches" empty-message="No appointment data yet." />
        </div>
    </div>

    <div class="rounded-2xl border border-surface-200 bg-white p-6">
        <h2 class="text-sm font-bold text-gray-900 mb-4">Top Services</h2>
        <x-admin.bar-list :items="$topServices" empty-message="No appointment data yet." />
    </div>
</x-layouts.admin>
