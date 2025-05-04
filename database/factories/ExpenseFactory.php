<?php

declare(strict_types=1);

namespace Database\Factories;

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
        return [
            'transaction_id' => null,
            'expense_type_id' => ExpenseType::factory(),
            'member_id' => Member::factory(),
            'date' => fake()->dateTime(),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
