@php
    $statusColors = ['pending' => 'amber', 'paid' => 'primary', 'failed' => 'red', 'refunded' => 'gray'];
@endphp

<x-layouts.admin title="Payments">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">All payments recorded against appointments.</p>
        <div class="rounded-2xl border border-surface-200 bg-white px-5 py-3">
            <p class="text-xs font-medium text-gray-500">Total Collected</p>
            <p class="text-xl font-bold text-primary-700">{{ number_format($totalCollected, 2) }}</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.payments.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <input
            type="text"
            name="search"
            value="{{ $filters['search'] ?? '' }}"
            placeholder="Search reference or patient..."
            class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
        <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Statuses</option>
            <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
            <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>Paid</option>
            <option value="failed" @selected(($filters['status'] ?? '') === 'failed')>Failed</option>
            <option value="refunded" @selected(($filters['status'] ?? '') === 'refunded')>Refunded</option>
        </select>
        <select name="method" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Methods</option>
            <option value="cash" @selected(($filters['method'] ?? '') === 'cash')>Cash</option>
            <option value="mobile_money" @selected(($filters['method'] ?? '') === 'mobile_money')>Mobile Money</option>
            <option value="card" @selected(($filters['method'] ?? '') === 'card')>Card</option>
            <option value="bank_transfer" @selected(($filters['method'] ?? '') === 'bank_transfer')>Bank Transfer</option>
        </select>
    </form>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Reference</th>
                        <th class="px-5 py-3">Patient</th>
                        <th class="px-5 py-3">Service</th>
                        <th class="px-5 py-3">Amount</th>
                        <th class="px-5 py-3">Method</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="px-5 py-4 font-mono text-xs text-gray-600">{{ $payment->reference }}</td>
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $payment->patient->name }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $payment->appointment->service->name }}</td>
                            <td class="px-5 py-4 font-medium text-gray-900">{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $payment->methodLabel() }}</td>
                            <td class="px-5 py-4">
                                <x-badge :color="$statusColors[$payment->status] ?? 'gray'">{{ $payment->statusLabel() }}</x-badge>
                            </td>
                            <td class="px-5 py-4 text-gray-500">{{ $payment->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-gray-500">No payments recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($payments->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
