<?php

namespace Database\Factories;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medicine>
 */
class MedicineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'category' => fake()->randomElement(['Pain Relief', 'Supplements', 'General']),
            'description' => fake()->sentence(),
            'quantity' => fake()->numberBetween(20, 100),
            'unit_price' => fake()->randomFloat(2, 500, 20000),
            'status' => Medicine::STATUS_ACTIVE,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => ['quantity' => fake()->numberBetween(0, Medicine::LOW_STOCK_THRESHOLD)]);
    }
}
