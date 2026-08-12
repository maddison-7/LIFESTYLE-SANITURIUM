<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['name', 'email', 'password', 'role', 'status', 'branch_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_CLINIC_ADMIN = 'clinic_admin';

    public const ROLE_RECEPTIONIST = 'receptionist';

    public const ROLE_HEALTHCARE_STAFF = 'healthcare_staff';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Super Admin and Clinic Admin always see every branch. A Receptionist
     * or Healthcare Staff sees only their assigned branch once one is set;
     * leaving branch_id null keeps their view unscoped (all branches).
     */
    public function isBranchScoped(): bool
    {
        return $this->branch_id !== null && ! $this->hasRole(self::ROLE_SUPER_ADMIN, self::ROLE_CLINIC_ADMIN);
    }
}
