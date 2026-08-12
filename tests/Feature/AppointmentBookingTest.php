<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_valid_booking_creates_a_pending_appointment_and_patient(): void
    {
        $service = Service::factory()->create();
        $branch = Branch::factory()->create();

        $response = $this->post(route('appointments.store'), [
            'full_name' => 'Jane Doe',
            'phone' => '0712345678',
            'email' => 'jane@example.com',
            'gender' => 'female',
            'service_id' => $service->id,
            'branch_id' => $branch->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '10:30',
            'message' => 'First visit',
        ]);

        $response->assertRedirect(route('appointments.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('patients', 1);
        $this->assertDatabaseHas('patients', ['phone' => '0712345678', 'name' => 'Jane Doe']);

        $appointment = Appointment::first();
        $this->assertNotNull($appointment);
        $this->assertSame(Appointment::STATUS_PENDING, $appointment->status);
        $this->assertNotEmpty($appointment->reference);
        $this->assertStringStartsWith('LSC-', $appointment->reference);
    }

    public function test_booking_with_the_same_phone_reuses_the_existing_patient(): void
    {
        $service = Service::factory()->create();
        $branch = Branch::factory()->create();
        $existing = Patient::factory()->create(['phone' => '0712345678']);

        $this->post(route('appointments.store'), [
            'full_name' => 'Jane Doe Updated',
            'phone' => '0712345678',
            'gender' => 'female',
            'service_id' => $service->id,
            'branch_id' => $branch->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '10:30',
        ]);

        $this->assertDatabaseCount('patients', 1);
        $this->assertSame($existing->id, Appointment::first()->patient_id);
    }

    public function test_a_past_date_is_rejected(): void
    {
        $service = Service::factory()->create();
        $branch = Branch::factory()->create();

        $response = $this->from(route('appointments.create'))->post(route('appointments.store'), [
            'full_name' => 'Jane Doe',
            'phone' => '0712345678',
            'gender' => 'female',
            'service_id' => $service->id,
            'branch_id' => $branch->id,
            'appointment_date' => now()->subDay()->toDateString(),
            'appointment_time' => '10:30',
        ]);

        $response->assertSessionHasErrors('appointment_date');
        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_an_inactive_service_cannot_be_booked(): void
    {
        $service = Service::factory()->inactive()->create();
        $branch = Branch::factory()->create();

        $response = $this->post(route('appointments.store'), [
            'full_name' => 'Jane Doe',
            'phone' => '0712345678',
            'gender' => 'female',
            'service_id' => $service->id,
            'branch_id' => $branch->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '10:30',
        ]);

        $response->assertSessionHasErrors('service_id');
    }

    public function test_booking_notifies_admin_receptionist_and_clinic_admin_roles(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $receptionist = User::factory()->receptionist()->create();
        $healthcareStaff = User::factory()->create(['role' => User::ROLE_HEALTHCARE_STAFF]);

        $service = Service::factory()->create();
        $branch = Branch::factory()->create();

        $this->post(route('appointments.store'), [
            'full_name' => 'Jane Doe',
            'phone' => '0712345678',
            'gender' => 'female',
            'service_id' => $service->id,
            'branch_id' => $branch->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '10:30',
        ]);

        $this->assertSame(1, Notification::where('user_id', $superAdmin->id)->count());
        $this->assertSame(1, Notification::where('user_id', $receptionist->id)->count());
        $this->assertSame(0, Notification::where('user_id', $healthcareStaff->id)->count());
    }
}
