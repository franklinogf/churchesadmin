<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CheckLayout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CheckLayout>
 */
final class CheckLayoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'width' => fake()->numberBetween(100, 500),
            'height' => fake()->numberBetween(100, 500),
            'fields' => [],
        ];
    }
}
