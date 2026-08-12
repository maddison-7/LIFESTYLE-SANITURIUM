<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_clinic_admin_can_create_a_service(): void
    {
        $admin = User::factory()->clinicAdmin()->create();

        $response = $this->actingAs($admin)->post(route('admin.services.store'), [
            'name' => 'New Consultation Service',
            'category' => 'General Wellness',
            'description' => 'A test service.',
            'status' => Service::STATUS_ACTIVE,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['name' => 'New Consultation Service', 'slug' => 'new-consultation-service']);
    }

    public function test_duplicate_service_names_get_unique_slugs(): void
    {
        $admin = User::factory()->clinicAdmin()->create();

        $this->actingAs($admin)->post(route('admin.services.store'), [
            'name' => 'Wellness Check', 'category' => 'General', 'status' => Service::STATUS_ACTIVE,
        ]);
        $this->actingAs($admin)->post(route('admin.services.store'), [
            'name' => 'Wellness Check', 'category' => 'General', 'status' => Service::STATUS_ACTIVE,
        ]);

        $this->assertDatabaseHas('services', ['slug' => 'wellness-check']);
        $this->assertDatabaseHas('services', ['slug' => 'wellness-check-2']);
    }

    public function test_a_service_with_appointment_history_cannot_be_deleted(): void
    {
        $admin = User::factory()->clinicAdmin()->create();
        $service = Service::factory()->create();
        Appointment::factory()->create(['service_id' => $service->id]);

        $response = $this->actingAs($admin)->delete(route('admin.services.destroy', $service));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('services', ['id' => $service->id]);
    }

    public function test_a_service_without_appointment_history_can_be_deleted(): void
    {
        $admin = User::factory()->clinicAdmin()->create();
        $service = Service::factory()->create();

        $this->actingAs($admin)->delete(route('admin.services.destroy', $service));

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }
}
