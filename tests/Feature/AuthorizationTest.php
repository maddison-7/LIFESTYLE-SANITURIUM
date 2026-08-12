<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_receptionist_is_forbidden_from_content_management_routes(): void
    {
        $receptionist = User::factory()->receptionist()->create();

        $this->actingAs($receptionist)->get(route('admin.services.index'))->assertForbidden();
        $this->actingAs($receptionist)->get(route('admin.branches.index'))->assertForbidden();
        $this->actingAs($receptionist)->get(route('admin.team.index'))->assertForbidden();
        $this->actingAs($receptionist)->get(route('admin.articles.index'))->assertForbidden();
        $this->actingAs($receptionist)->get(route('admin.medicines.index'))->assertForbidden();
        $this->actingAs($receptionist)->get(route('admin.inventory.index'))->assertForbidden();
        $this->actingAs($receptionist)->get(route('admin.reports.appointments'))->assertForbidden();
        $this->actingAs($receptionist)->get(route('admin.settings.edit'))->assertForbidden();
        $this->actingAs($receptionist)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_receptionist_can_access_appointments_patients_and_notifications(): void
    {
        $receptionist = User::factory()->receptionist()->create();

        $this->actingAs($receptionist)->get(route('admin.appointments.index'))->assertOk();
        $this->actingAs($receptionist)->get(route('admin.patients.index'))->assertOk();
        $this->actingAs($receptionist)->get(route('admin.notifications.index'))->assertOk();
        $this->actingAs($receptionist)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_clinic_admin_can_access_content_management_but_not_user_management(): void
    {
        $clinicAdmin = User::factory()->clinicAdmin()->create();

        $this->actingAs($clinicAdmin)->get(route('admin.services.index'))->assertOk();
        $this->actingAs($clinicAdmin)->get(route('admin.settings.edit'))->assertOk();
        $this->actingAs($clinicAdmin)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_only_super_admin_can_manage_users(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($superAdmin)->get(route('admin.users.create'))->assertOk();
    }
}
