<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentAndAnalyticsAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_receptionist_can_access_payments_but_not_analytics(): void
    {
        $receptionist = User::factory()->receptionist()->create();

        $this->actingAs($receptionist)->get(route('admin.payments.index'))->assertOk();
        $this->actingAs($receptionist)->get(route('admin.analytics'))->assertForbidden();
    }

    public function test_clinic_admin_can_access_both(): void
    {
        $admin = User::factory()->clinicAdmin()->create();

        $this->actingAs($admin)->get(route('admin.payments.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.analytics'))->assertOk();
    }
}
