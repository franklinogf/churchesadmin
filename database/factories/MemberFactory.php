<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Models\DeactivationCode;
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
            'baptism_date' => fake()->optional()->date(),
            'civil_status' => fake()->randomElement(CivilStatus::values()),
            'active' => true,
        ];
    }

    /**
     * Indicate that the member is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
            'deactivation_code_id' => null,
        ]);
    }

    /**
     * Indicate that the member is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
            'deactivation_code_id' => DeactivationCode::factory(),
        ]);
    }
}
