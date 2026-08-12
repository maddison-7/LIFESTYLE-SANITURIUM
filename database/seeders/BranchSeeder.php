<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Seed only the two branches confirmed by the client. Phone, opening
     * hours and map link are left blank where not supplied, to be filled
     * in later from the admin dashboard.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Buguruni Malapa',
                'location' => 'Buguruni Malapa, Dar es Salaam, Tanzania',
                'phone' => '0713 999 255',
                'status' => Branch::STATUS_OPEN,
                'sort_order' => 1,
            ],
            [
                'name' => 'USA-River, Arusha',
                'location' => 'USA-River, Arusha, Tanzania',
                'phone' => null,
                'status' => Branch::STATUS_COMING_SOON,
                'sort_order' => 2,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::query()->updateOrCreate(
                ['name' => $branch['name']],
                $branch
            );
        }
    }
}
