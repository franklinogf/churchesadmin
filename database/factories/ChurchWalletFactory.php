<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChurchWallet>
 */
final class ChurchWalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word();

        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'description' => fake()->optional()->sentence(),
            'bank_name' => fake()->company(),
            'bank_routing_number' => fake()->randomNumber(9),
            'bank_account_number' => fake()->randomNumber(9),
            'check_layout_id' => null,
        ];
    }
}
