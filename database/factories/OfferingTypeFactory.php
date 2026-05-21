<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OfferingType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OfferingType>
 */
final class OfferingTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
