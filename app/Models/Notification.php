<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    public const STATUS_UNREAD = 'unread';

    public const STATUS_READ = 'read';

    public const TYPE_APPOINTMENT = 'appointment';

    public const TYPE_INVENTORY = 'inventory';

    public const TYPE_SYSTEM = 'system';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    public function markAsRead(): void
    {
        if ($this->status !== self::STATUS_READ) {
            $this->update(['status' => self::STATUS_READ]);
        }
    }

    /**
     * Notify every active user in the given roles. Used at trigger points
     * (new appointment, low stock) rather than a broadcast/pivot table,
     * matching the notifications schema's single user_id per row.
     */
    public static function notifyRoles(array $roles, string $title, string $message, string $type): void
    {
        User::query()
            ->whereIn('role', $roles)
            ->where('status', User::STATUS_ACTIVE)
            ->pluck('id')
            ->each(fn (int $userId) => self::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
            ]));
    }
}
