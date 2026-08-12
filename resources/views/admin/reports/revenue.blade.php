<x-layouts.admin title="Revenue Reports">
    @include('admin.reports._tabs')
    @include('admin.reports._date_filter', ['route' => 'admin.reports.revenue'])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <p class="text-sm font-medium text-gray-500">Total Collected</p>
            <p class="mt-2 text-3xl font-bold text-primary-700">{{ number_format($total, 2) }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $count }} payment{{ $count === 1 ? '' : 's' }} &middot; {{ $from->format('d M Y') }} &ndash; {{ $to->format('d M Y') }}</p>
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">By Payment Method</h2>
            <x-admin.bar-list :items="$byMethod" empty-message="No payments in this date range." />
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">By Branch</h2>
            <x-admin.bar-list :items="$byBranch" empty-message="No payments in this date range." />
        </div>
    </div>
</x-layouts.admin>
