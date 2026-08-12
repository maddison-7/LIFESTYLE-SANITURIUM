<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->numerify('07########'),
            'email' => fake()->optional()->safeEmail(),
            'gender' => fake()->randomElement(['male', 'female']),
        ];
    }
}
