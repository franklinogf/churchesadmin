<?php

declare(strict_types=1);

use App\Enums\CheckType;
use App\Enums\TenantPermission;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('checks.create'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CHECKS_MANAGE, TenantPermission::CHECKS_CREATE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('checks.create'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('checks/create')
                ->has('walletOptions')
                ->has('memberOptions')
                ->has('checkTypesOptions')
                ->has('expenseTypesOptions')
            );
    });

    it('can store a check', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $wallet->depositFloat('200.00'); // Add sufficient balance
        $member = Member::factory()->create();
        $expenseType = ExpenseType::factory()->create();

        $checkData = [
            'amount' => '100.00',
            'member_id' => (string) $member->id,
            'date' => '2025-06-07',
            'type' => CheckType::PAYMENT->value,
            'wallet_id' => (string) $wallet->id,
            'note' => 'Test check',
            'expense_type_id' => (string) $expenseType->id,
        ];

        $this->from(route('checks.create'))
            ->post(route('checks.store'), $checkData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('checks.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('checks', [
            'member_id' => $member->id,
            'date' => '2025-06-07',
            'type' => CheckType::PAYMENT->value,
        ]);
    });

    it('validates required fields', function (): void {
        $this->from(route('checks.create'))
            ->post(route('checks.store'), [])
            ->assertSessionHasErrors(['amount', 'member_id', 'date', 'type', 'wallet_id', 'expense_type_id']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the create check form', function (): void {
        get(route('checks.create'))
            ->assertStatus(403);
    });

    it('cannot store a check', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $wallet->depositFloat('200.00'); // Add sufficient balance
        $member = Member::factory()->create();
        $expenseType = ExpenseType::factory()->create();

        $checkData = [
            'amount' => '100.00',
            'member_id' => (string) $member->id,
            'date' => '2025-06-07',
            'type' => CheckType::PAYMENT->value,
            'wallet_id' => (string) $wallet->id,
            'expense_type_id' => (string) $expenseType->id,
        ];

        $this->post(route('checks.store'), $checkData)
            ->assertStatus(403);
    });
});
