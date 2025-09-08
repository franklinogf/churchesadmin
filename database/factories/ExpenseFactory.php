<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TransactionMetaType;
use App\Models\ChurchWallet;
use App\Models\CurrentYear;
use App\Models\ExpenseType;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
final class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currentYear = CurrentYear::first() ?? CurrentYear::factory()->create();
        $wallet = ChurchWallet::factory()->withBalance()->create();
        $amount = fake()->randomFloat(2, 1, 100);
        $transaction = $wallet->withdrawFloat($amount, ['type' => TransactionMetaType::EXPENSE->value, 'year' => $currentYear->id]);

        return [
            'transaction_id' => $transaction->id,
            'expense_type_id' => ExpenseType::factory(),
            'member_id' => Member::factory(),
            'date' => fake()->dateTime(),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
