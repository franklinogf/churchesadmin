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

        return [
            'name' => fake()->firstName($gender),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'gender' => $gender,
            'church' => fake()->company(),
            'offering' => fake()->randomFloat(2, 10, 1000),
            'offering_frequency' => fake()->randomElement(OfferingFrequency::values()),
        ];
    }
}
