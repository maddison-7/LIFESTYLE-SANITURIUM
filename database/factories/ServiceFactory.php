<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'category' => fake()->randomElement(["Men's Health", "Women's Health", 'General Wellness']),
            'description' => fake()->sentence(),
            'status' => Service::STATUS_ACTIVE,
            'sort_order' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['status' => Service::STATUS_INACTIVE]);
    }
}
