<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city(),
            'location' => fake()->address(),
            'phone' => fake()->numerify('07########'),
            'status' => Branch::STATUS_OPEN,
            'sort_order' => 0,
        ];
    }

    public function comingSoon(): static
    {
        return $this->state(fn (array $attributes) => ['status' => Branch::STATUS_COMING_SOON]);
    }
}
