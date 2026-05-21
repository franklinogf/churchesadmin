<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ChurchWallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChurchWallet>
 */
final class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
