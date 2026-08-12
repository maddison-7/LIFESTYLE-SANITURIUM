<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'open';

    public const STATUS_COMING_SOON = 'coming_soon';

    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name',
        'slug',
        'location',
        'phone',
        'status',
        'opening_hours',
        'map_link',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::creating(function (Branch $branch): void {
            if (empty($branch->slug)) {
                $branch->slug = self::uniqueSlug($branch->name);
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $base.'-'.++$suffix;
        }

        return $slug;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', '!=', self::STATUS_CLOSED);
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'Open',
            self::STATUS_COMING_SOON => 'Coming Soon',
            self::STATUS_CLOSED => 'Closed',
            default => ucfirst($this->status),
        };
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
