<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\ChurchWallet;
use App\Models\Expense;
use App\Models\ExpenseType;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    $expense = Expense::factory()->create();

    get(route('expenses.edit', $expense))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::EXPENSES_MANAGE, TenantPermission::EXPENSES_UPDATE);
    });

    it('can be rendered if authenticated', function (): void {
        $expense = Expense::factory()->create();

        get(route('expenses.edit', $expense))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('expenses/edit')
                ->has('expense')
                ->has('memberOptions')
                ->has('wallets')
                ->has('expenseTypesOptions')
                ->has('walletOptions')
                ->where('expense.id', $expense->id)
            );
    });
    it('can update an expense', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $wallet->depositFloat('500.00');
        $expenseType = ExpenseType::factory()->create();

        // Create an expense with the factory
        $expense = Expense::factory()->create();

        // Ensure the original expense's wallet has sufficient balance
        $originalWallet = $expense->transaction->wallet->holder;
        if ($originalWallet instanceof ChurchWallet) {
            $originalWallet->depositFloat('500.00');
        }

        $updateData = [
            'date' => '2025-06-07',
            'wallet_id' => (string) $wallet->id,
            'expense_type_id' => (string) $expenseType->id,
            'amount' => '150.00',
            'note' => 'Updated expense',
        ];

        $this->from(route('expenses.edit', $expense))
            ->put(route('expenses.update', $expense), $updateData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('expenses.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'date' => '2025-06-07',
            'expense_type_id' => $expenseType->id,
        ]);
    });

    it('validates required fields on update', function (): void {
        $expense = Expense::factory()->create();

        $this->from(route('expenses.edit', $expense))
            ->put(route('expenses.update', $expense), [])
            ->assertSessionHasErrors(['date', 'wallet_id', 'expense_type_id', 'amount']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the edit expense form', function (): void {
        $expense = Expense::factory()->create();

        get(route('expenses.edit', $expense))
            ->assertStatus(403);
    });

    it('cannot update an expense', function (): void {
        $expense = Expense::factory()->create();
        $wallet = ChurchWallet::factory()->create();
        $expenseType = ExpenseType::factory()->create();

        $updateData = [
            'date' => '2025-06-07',
            'wallet_id' => (string) $wallet->id,
            'expense_type_id' => (string) $expenseType->id,
            'amount' => '150.00',
        ];

        $this->put(route('expenses.update', $expense), $updateData)
            ->assertStatus(403);
    });
});
