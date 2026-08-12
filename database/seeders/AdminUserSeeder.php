<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed a single super admin account so the dashboard is reachable out
     * of the box. Credentials come from .env (dev-only defaults) and must
     * be rotated before any real deployment.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => config('clinic.admin_email')],
            [
                'name' => 'Super Admin',
                'password' => Hash::make(config('clinic.admin_password')),
                'role' => User::ROLE_SUPER_ADMIN,
                'status' => User::STATUS_ACTIVE,
            ]
        );
    }
}
