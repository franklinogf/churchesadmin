<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
final class MemberFactory extends Factory
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
            'email' => fake()->optional()->unique()?->safeEmail(),
            'phone' => fake()->optional()->unique()?->e164PhoneNumber(),
            'gender' => $gender,
            'dob' => fake()->date(),
            'civil_status' => fake()->randomElement(CivilStatus::values()),
        ];
    }
}
