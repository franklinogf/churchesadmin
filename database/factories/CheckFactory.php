<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CheckType;
use App\Enums\TransactionMetaType;
use App\Models\Check;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Check>
 */
final class CheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $wallet = ChurchWallet::factory()->withBalance()->create();
        $amount = fake()->randomFloat(2, 1, 100);
        $transaction = $wallet->withdrawFloat($amount, ['type' => TransactionMetaType::CHECK->value], false);

        return [
            'transaction_id' => $transaction->id,
            'expense_type_id' => ExpenseType::factory(),
            'member_id' => Member::factory(),
            'check_number' => fake()->optional()->numerify('#####'),
            'date' => fake()->dateTime(),
            'type' => fake()->randomElement(CheckType::cases())->value,
            'note' => fake()->optional()->sentence(),
        ];
    }

    public function confirmed(): static
    {
        return $this->afterCreating(function (Check $check) {
            $check->transaction->wallet->confirm($check->transaction);
        });
    }

    public function unconfirmed(): static
    {
        return $this->afterCreating(function (Check $check) {
            // Transaction is unconfirmed by default, no action needed
        });
    }
}
