<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    private const MONTHS = 6;

    public function __invoke(): View
    {
        $rangeStart = now()->subMonths(self::MONTHS - 1)->startOfMonth();

        $appointments = Appointment::query()->where('created_at', '>=', $rangeStart)->get();
        $payments = Payment::query()->paid()->where('paid_at', '>=', $rangeStart)->get();

        return view('admin.analytics.index', [
            'monthlyAppointments' => $this->monthlyBuckets($appointments, 'created_at', fn ($rows) => $rows->count()),
            'monthlyRevenue' => $this->monthlyBuckets($payments, 'paid_at', fn ($rows) => (float) $rows->sum('amount')),
            'funnel' => $this->conversionFunnel($appointments),
            'topServices' => $this->topServices(),
            'topBranches' => $this->topBranches(),
            'monthsCovered' => self::MONTHS,
        ]);
    }

    /**
     * @return array<int, array{label: string, value: int|float}>
     */
    private function monthlyBuckets($rows, string $dateColumn, callable $aggregate): array
    {
        $buckets = [];

        for ($i = self::MONTHS - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');
            $buckets[$key] = ['label' => $month->format('M Y'), 'value' => 0];
        }

        $grouped = $rows->groupBy(fn ($row) => Carbon::parse($row->{$dateColumn})->format('Y-m'));

        foreach ($buckets as $key => $bucket) {
            if ($grouped->has($key)) {
                $buckets[$key]['value'] = $aggregate($grouped->get($key));
            }
        }

        return array_values($buckets);
    }

    /**
     * @return array{stages: array<int, array{label: string, value: int, color: string}>, completionRate: float, cancellationRate: float}
     */
    private function conversionFunnel($appointments): array
    {
        $total = $appointments->count();
        $confirmed = $appointments->whereIn('status', ['confirmed', 'rescheduled', 'completed'])->count();
        $completed = $appointments->where('status', 'completed')->count();
        $cancelled = $appointments->where('status', 'cancelled')->count();

        return [
            'stages' => [
                ['label' => 'Requested', 'value' => $total, 'color' => 'bg-primary-600'],
                ['label' => 'Confirmed', 'value' => $confirmed, 'color' => 'bg-primary-500'],
                ['label' => 'Completed', 'value' => $completed, 'color' => 'bg-primary-400'],
                ['label' => 'Cancelled', 'value' => $cancelled, 'color' => 'bg-red-400'],
            ],
            'completionRate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0.0,
            'cancellationRate' => $total > 0 ? round(($cancelled / $total) * 100, 1) : 0.0,
        ];
    }

    private function topServices(): array
    {
        $counts = Appointment::query()->selectRaw('service_id, count(*) as total')->groupBy('service_id')->pluck('total', 'service_id');

        return Service::query()->get()->map(fn (Service $s) => [
            'label' => $s->name,
            'value' => $counts->get($s->id, 0),
        ])->sortByDesc('value')->take(5)->values()->all();
    }

    private function topBranches(): array
    {
        $counts = Appointment::query()->selectRaw('branch_id, count(*) as total')->groupBy('branch_id')->pluck('total', 'branch_id');

        return Branch::query()->get()->map(fn (Branch $b) => [
            'label' => $b->name,
            'value' => $counts->get($b->id, 0),
        ])->sortByDesc('value')->take(5)->values()->all();
    }
}
