<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Missionary>
 */
final class MissionaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(Gender::values());
        $offering = fake()->optional()->randomFloat(2, 10, 1000);

        return [
            'name' => fake()->firstName($gender),
            'last_name' => fake()->lastName(),
            'email' => fake()->optional()->unique()?->safeEmail(),
            'phone' => fake()->optional(0.8)->unique()?->e164PhoneNumber(),
            'gender' => $gender,
            'church' => fake()->optional()->company(),
            'offering' => $offering,
            'offering_frequency' => $offering ? fake()->randomElement(OfferingFrequency::values()) : null,
        ];
    }
}
