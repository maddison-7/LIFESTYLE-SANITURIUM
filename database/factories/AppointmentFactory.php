<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'service_id' => Service::factory(),
            'branch_id' => Branch::factory(),
            'appointment_date' => fake()->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
            'appointment_time' => fake()->time('H:i:s'),
            'status' => Appointment::STATUS_PENDING,
        ];
    }

    public function status(string $status): static
    {
        return $this->state(fn (array $attributes) => ['status' => $status]);
    }
}
