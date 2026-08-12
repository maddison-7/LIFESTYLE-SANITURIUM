<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_confirm_a_pending_appointment(): void
    {
        $receptionist = User::factory()->receptionist()->create();
        $appointment = Appointment::factory()->status(Appointment::STATUS_PENDING)->create();

        $response = $this->actingAs($receptionist)->patch(route('admin.appointments.updateStatus', $appointment), [
            'status' => Appointment::STATUS_CONFIRMED,
        ]);

        $response->assertRedirect();
        $this->assertSame(Appointment::STATUS_CONFIRMED, $appointment->fresh()->status);
    }

    public function test_changing_the_date_automatically_marks_the_appointment_rescheduled(): void
    {
        $receptionist = User::factory()->receptionist()->create();
        $appointment = Appointment::factory()->status(Appointment::STATUS_CONFIRMED)->create([
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '09:00:00',
        ]);

        $this->actingAs($receptionist)->put(route('admin.appointments.update', $appointment), [
            'appointment_date' => now()->addDays(5)->toDateString(),
            'appointment_time' => '14:00',
            'admin_notes' => 'Patient asked to move the date.',
        ]);

        $fresh = $appointment->fresh();
        $this->assertSame(Appointment::STATUS_RESCHEDULED, $fresh->status);
        $this->assertSame('Patient asked to move the date.', $fresh->admin_notes);
    }

    public function test_a_completed_appointment_does_not_flip_back_to_rescheduled_on_notes_only_edits(): void
    {
        $receptionist = User::factory()->receptionist()->create();
        $appointment = Appointment::factory()->status(Appointment::STATUS_COMPLETED)->create([
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '09:00:00',
        ]);

        $this->actingAs($receptionist)->put(route('admin.appointments.update', $appointment), [
            'appointment_date' => $appointment->appointment_date->toDateString(),
            'appointment_time' => '09:00',
            'admin_notes' => 'Follow-up note only.',
        ]);

        $this->assertSame(Appointment::STATUS_COMPLETED, $appointment->fresh()->status);
    }
}
