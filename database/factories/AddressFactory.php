<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AddressType;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
final class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile_id' => Profile::factory(),
            'line_1' => fake()->streetAddress(),
            'line_2' => fake()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->countryCode(),
            'address_type' => fake()->randomElement(AddressType::values()),
            'is_primary' => fake()->boolean(),
        ];
    }

    public function notPrimary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => false,
        ]);
    }

    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }
}
