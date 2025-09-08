<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('expenses.create'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::EXPENSES_MANAGE, TenantPermission::EXPENSES_CREATE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('expenses.create'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('expenses/create')
                ->has('memberOptions')
                ->has('wallets')
                ->has('walletOptions')
                ->has('expenseTypes')
                ->has('expenseTypesOptions')
            );
    });

    it('can store an expense', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $wallet->depositFloat('200.00'); // Add balance to wallet
        $member = Member::factory()->create();
        $expenseType = ExpenseType::factory()->create();

        $expenseData = [
            'expenses' => [
                [
                    'date' => '2025-06-07',
                    'wallet_id' => (string) $wallet->id,
                    'member_id' => (string) $member->id,
                    'expense_type_id' => (string) $expenseType->id,
                    'amount' => '100.00',
                    'note' => 'Test expense',
                ],
            ],
        ];

        $this->from(route('expenses.create'))
            ->post(route('expenses.store'), $expenseData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('expenses.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', [
            'date' => '2025-06-07',
            'member_id' => $member->id,
            'expense_type_id' => $expenseType->id,
        ]);
    });

    it('validates required fields', function (): void {
        $this->from(route('expenses.create'))
            ->post(route('expenses.store'), [])
            ->assertSessionHasErrors(['expenses']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the create expense form', function (): void {
        get(route('expenses.create'))
            ->assertStatus(403);
    });

    it('cannot store an expense', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $expenseType = ExpenseType::factory()->create();

        $expenseData = [
            'expenses' => [
                [
                    'date' => '2025-06-07',
                    'wallet_id' => (string) $wallet->id,
                    'expense_type_id' => (string) $expenseType->id,
                    'amount' => '100.00',
                ],
            ],
        ];

        $this->post(route('expenses.store'), $expenseData)
            ->assertStatus(403);
    });
});
