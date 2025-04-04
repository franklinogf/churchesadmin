<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use App\Enums\LanguageCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
final class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'lastname' => fake()->lastName(),
            'dob' => fake()->date(),
            'gender' => fake()->randomElement(Gender::values()),
            'civil_status' => fake()->randomElement(CivilStatus::values()),
            'is_active' => fake()->boolean(),
            'phone' => fake()->phoneNumber(),
            'language' => fake()->randomElement(LanguageCode::values()),
        ];
    }
}
