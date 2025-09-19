<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
final class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->optional()->unique()?->e164PhoneNumber(),
            'email' => fake()->optional()->unique()?->safeEmail(),
            'first_visit_date' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
