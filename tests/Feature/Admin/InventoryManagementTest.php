<?php

namespace Tests\Feature\Admin;

use App\Models\Medicine;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_in_increases_quantity_and_logs_a_transaction(): void
    {
        $admin = User::factory()->clinicAdmin()->create();
        $medicine = Medicine::factory()->create(['quantity' => 50]);

        $this->actingAs($admin)->post(route('admin.inventory.store'), [
            'medicine_id' => $medicine->id,
            'type' => 'in',
            'quantity' => 20,
            'reference' => 'Supplier delivery',
        ]);

        $this->assertSame(70, $medicine->fresh()->quantity);
        $this->assertDatabaseHas('inventory_transactions', [
            'medicine_id' => $medicine->id,
            'type' => 'in',
            'quantity' => 20,
        ]);
    }

    public function test_stock_out_decreases_quantity(): void
    {
        $admin = User::factory()->clinicAdmin()->create();
        $medicine = Medicine::factory()->create(['quantity' => 50]);

        $this->actingAs($admin)->post(route('admin.inventory.store'), [
            'medicine_id' => $medicine->id,
            'type' => 'out',
            'quantity' => 15,
        ]);

        $this->assertSame(35, $medicine->fresh()->quantity);
    }

    public function test_stock_out_cannot_exceed_current_quantity(): void
    {
        $admin = User::factory()->clinicAdmin()->create();
        $medicine = Medicine::factory()->create(['quantity' => 10]);

        $response = $this->actingAs($admin)->post(route('admin.inventory.store'), [
            'medicine_id' => $medicine->id,
            'type' => 'out',
            'quantity' => 999,
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertSame(10, $medicine->fresh()->quantity);
    }

    public function test_crossing_the_low_stock_threshold_notifies_admins_once(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $medicine = Medicine::factory()->create(['quantity' => 15]);

        $admin = User::factory()->clinicAdmin()->create();

        // 15 -> 8: crosses the low-stock threshold (10), should notify.
        $this->actingAs($admin)->post(route('admin.inventory.store'), [
            'medicine_id' => $medicine->id, 'type' => 'out', 'quantity' => 7,
        ]);
        $this->assertSame(1, Notification::where('user_id', $superAdmin->id)->count());

        // 8 -> 5: already low, should not notify again.
        $this->actingAs($admin)->post(route('admin.inventory.store'), [
            'medicine_id' => $medicine->id, 'type' => 'out', 'quantity' => 3,
        ]);
        $this->assertSame(1, Notification::where('user_id', $superAdmin->id)->count());
    }
}
