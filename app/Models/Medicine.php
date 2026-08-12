<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const LOW_STOCK_THRESHOLD = 10;

    protected $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'unit_price',
        'expiry_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'unit_price' => 'decimal:2',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where('name', 'like', '%'.$term.'%');
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->where('quantity', '<=', self::LOW_STOCK_THRESHOLD);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= self::LOW_STOCK_THRESHOLD;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function statusLabel(): string
    {
        return ucfirst($this->status);
    }
}
