<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    public const STATUS_REFUNDED = 'refunded';

    public const METHODS = ['cash', 'mobile_money', 'card', 'bank_transfer'];

    protected $fillable = [
        'reference',
        'appointment_id',
        'patient_id',
        'amount',
        'method',
        'gateway',
        'gateway_reference',
        'status',
        'notes',
        'recorded_by',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment): void {
            if (empty($payment->reference)) {
                $payment->reference = self::generateReference();
            }
        });
    }

    public static function generateReference(): string
    {
        do {
            $reference = 'RCT-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function statusLabel(): string
    {
        return ucfirst($this->status);
    }

    public function methodLabel(): string
    {
        return match ($this->method) {
            'mobile_money' => 'Mobile Money',
            'bank_transfer' => 'Bank Transfer',
            default => ucfirst($this->method),
        };
    }
}
