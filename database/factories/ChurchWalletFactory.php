<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TransactionMetaType;
use App\Models\ChurchWallet;
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
        $name = fake()->unique()->word();

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

    public function withBalance(string $amount = '100.00'): Factory
    {
        return $this->afterCreating(function (ChurchWallet $wallet) use ($amount): void {
            $wallet->depositFloat($amount, ['type' => TransactionMetaType::INITIAL->value]);
        });
    }
}
