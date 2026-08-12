<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Seed the seven core service categories the clinic offers, as listed
     * in the brand brief. Descriptions are kept general and non-clinical;
     * detailed content can be refined later from the admin dashboard.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => "Men's Reproductive Health",
                'category' => "Men's Health",
                'description' => 'Confidential consultation, evaluation and care for men\'s reproductive health concerns.',
                'sort_order' => 1,
            ],
            [
                'name' => "Women's Reproductive Health",
                'category' => "Women's Health",
                'description' => 'Confidential consultation, evaluation and care for women\'s reproductive health concerns.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Urinary System Health',
                'category' => 'Urinary Health',
                'description' => 'Assessment and care for conditions affecting the urinary system.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Counselling & Consultation',
                'category' => 'Consultation',
                'description' => 'Professional, confidential consultation sessions with our healthcare team.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Medical Testing',
                'category' => 'Diagnostics',
                'description' => 'Medical testing services to support accurate diagnosis and informed care.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Treatment & Follow-Up',
                'category' => 'Treatment',
                'description' => 'Treatment and structured follow-up care for patients under our clinic\'s guidance.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Medicines & Health Products',
                'category' => 'Pharmacy',
                'description' => 'Medicines and health products related to the services offered at our clinic.',
                'sort_order' => 7,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(
                ['name' => $service['name']],
                $service + ['status' => Service::STATUS_ACTIVE]
            );
        }
    }
}
