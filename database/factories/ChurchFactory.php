<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Church;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Church>
 */
final class ChurchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'name' => fake()->company(),
            'locale' => fake()->randomElement(['en', 'es']),
            'active' => fake()->boolean(90), // 90% chance of being active
            'data' => null,
        ];
    }

    public function active(): static
    {
        return $this->state([
            'active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state([
            'active' => false,
        ]);
    }
}
