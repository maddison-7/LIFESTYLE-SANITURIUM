<?php

namespace Database\Factories;

use App\Models\HealthArticle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HealthArticle>
 */
class HealthArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(4),
            'category' => fake()->randomElement(HealthArticle::CATEGORIES),
            'excerpt' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'status' => HealthArticle::STATUS_DRAFT,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => HealthArticle::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);
    }
}
