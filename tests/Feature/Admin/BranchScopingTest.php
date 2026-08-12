<?php

namespace Tests\Feature\Admin;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_branch_scoped_receptionist_only_sees_their_branch_appointments(): void
    {
        $branchA = Branch::factory()->create(['name' => 'Branch A']);
        $branchB = Branch::factory()->create(['name' => 'Branch B']);

        $receptionist = User::factory()->receptionist()->create(['branch_id' => $branchA->id]);

        $appointmentA = Appointment::factory()->create(['branch_id' => $branchA->id]);
        $appointmentB = Appointment::factory()->create(['branch_id' => $branchB->id]);

        $response = $this->actingAs($receptionist)->get(route('admin.appointments.index'));

        $response->assertOk();
        $response->assertSee($appointmentA->reference);
        $response->assertDontSee($appointmentB->reference);
    }

    public function test_a_branch_scoped_receptionist_cannot_open_another_branchs_appointment_directly(): void
    {
        $branchA = Branch::factory()->create();
        $branchB = Branch::factory()->create();
        $receptionist = User::factory()->receptionist()->create(['branch_id' => $branchA->id]);
        $foreignAppointment = Appointment::factory()->create(['branch_id' => $branchB->id]);

        $this->actingAs($receptionist)
            ->get(route('admin.appointments.show', $foreignAppointment))
            ->assertNotFound();

        $this->actingAs($receptionist)
            ->patch(route('admin.appointments.updateStatus', $foreignAppointment), ['status' => 'confirmed'])
            ->assertNotFound();
    }

    public function test_an_unassigned_receptionist_sees_all_branches(): void
    {
        $branchA = Branch::factory()->create();
        $branchB = Branch::factory()->create();
        $receptionist = User::factory()->receptionist()->create(['branch_id' => null]);

        $appointmentA = Appointment::factory()->create(['branch_id' => $branchA->id]);
        $appointmentB = Appointment::factory()->create(['branch_id' => $branchB->id]);

        $response = $this->actingAs($receptionist)->get(route('admin.appointments.index'));

        $response->assertSee($appointmentA->reference);
        $response->assertSee($appointmentB->reference);
    }

    public function test_a_clinic_admin_sees_all_branches_even_if_a_branch_is_set(): void
    {
        $branchA = Branch::factory()->create();
        $branchB = Branch::factory()->create();
        // A branch_id on an admin role should simply be ignored by isBranchScoped().
        $admin = User::factory()->clinicAdmin()->create(['branch_id' => $branchA->id]);

        $appointmentB = Appointment::factory()->create(['branch_id' => $branchB->id]);

        $this->actingAs($admin)
            ->get(route('admin.appointments.show', $appointmentB))
            ->assertOk();
    }
}
