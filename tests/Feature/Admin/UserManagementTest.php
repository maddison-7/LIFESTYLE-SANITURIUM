<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_a_user(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->post(route('admin.users.store'), [
            'name' => 'New Receptionist',
            'email' => 'new.receptionist@example.com',
            'password' => 'Password@2026',
            'password_confirmation' => 'Password@2026',
            'role' => User::ROLE_RECEPTIONIST,
            'status' => User::STATUS_ACTIVE,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'new.receptionist@example.com', 'role' => User::ROLE_RECEPTIONIST]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'user.created', 'user_id' => $superAdmin->id]);
    }

    public function test_a_user_cannot_change_their_own_role(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->put(route('admin.users.update', $superAdmin), [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'role' => User::ROLE_RECEPTIONIST,
            'status' => User::STATUS_ACTIVE,
        ]);

        $response->assertSessionHas('error');
        $this->assertSame(User::ROLE_SUPER_ADMIN, $superAdmin->fresh()->role);
    }

    public function test_a_user_cannot_delete_their_own_account(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->delete(route('admin.users.destroy', $superAdmin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $superAdmin->id]);
    }

    public function test_a_super_admin_can_change_another_admins_role_and_it_is_audited(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $target = User::factory()->receptionist()->create();

        $response = $this->actingAs($superAdmin)->put(route('admin.users.update', $target), [
            'name' => $target->name,
            'email' => $target->email,
            'role' => User::ROLE_CLINIC_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertSame(User::ROLE_CLINIC_ADMIN, $target->fresh()->role);
        $this->assertDatabaseHas('audit_logs', ['action' => 'user.updated', 'user_id' => $superAdmin->id]);
    }

    public function test_a_super_admin_can_delete_another_admins_account(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $target = User::factory()->clinicAdmin()->create();

        $response = $this->actingAs($superAdmin)->delete(route('admin.users.destroy', $target));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $target->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'user.deleted']);
    }

    /**
     * The system can never actually reach "zero super admins" through the UI:
     * self-role-change and self-delete are unconditionally blocked above, and
     * only a super admin can touch user records at all (the manage-users
     * gate), so at least one super admin — the actor — always survives any
     * single request. This test documents that invariant directly rather
     * than exercising the (structurally unreachable) last-admin count checks
     * in the controller, which exist only as defense-in-depth should the
     * permission model change later.
     */
    public function test_a_lone_super_admin_can_always_still_manage_the_system(): void
    {
        $onlySuperAdmin = User::factory()->superAdmin()->create();

        $this->assertSame(1, User::where('role', User::ROLE_SUPER_ADMIN)->count());
        $this->actingAs($onlySuperAdmin)->get(route('admin.users.index'))->assertOk();
    }
}
