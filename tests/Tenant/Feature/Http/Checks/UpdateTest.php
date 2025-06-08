<?php

declare(strict_types=1);

use App\Enums\CheckType;
use App\Enums\TenantPermission;
use App\Models\Check;
use App\Models\ChurchWallet;
use App\Models\ExpenseType;
use App\Models\Member;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    $check = Check::factory()->create();

    get(route('checks.edit', $check))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CHECKS_MANAGE, TenantPermission::CHECKS_UPDATE);
    });

    it('can be rendered if authenticated', function (): void {
        $check = Check::factory()->create();

        get(route('checks.edit', $check))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('checks/edit')
                ->has('walletOptions')
                ->has('memberOptions')
                ->has('checkTypesOptions')
                ->has('expenseTypesOptions')
                ->has('check')
                ->where('check.id', $check->id)
            );
    });

    it('can update a check', function (): void {
        $wallet = ChurchWallet::factory()->create();
        $wallet->depositFloat('200.00'); // Add sufficient balance
        $member = Member::factory()->create();
        $expenseType = ExpenseType::factory()->create();
        $check = Check::factory()->create();

        $updateData = [
            'amount' => '150.00',
            'member_id' => (string) $member->id,
            'date' => '2025-06-07',
            'type' => CheckType::PAYMENT->value,
            'wallet_id' => (string) $wallet->id,
            'note' => 'Updated check',
            'expense_type_id' => (string) $expenseType->id,
        ];

        $this->from(route('checks.edit', $check))
            ->put(route('checks.update', $check), $updateData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('checks.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('checks', [
            'id' => $check->id,
            'member_id' => $member->id,
            'date' => '2025-06-07',
            'type' => CheckType::PAYMENT->value,
        ]);
    });

    it('validates required fields on update', function (): void {
        $check = Check::factory()->create();

        $this->from(route('checks.edit', $check))
            ->put(route('checks.update', $check), [])
            ->assertSessionHasErrors(['amount', 'member_id', 'date', 'type', 'wallet_id', 'expense_type_id']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the edit check form', function (): void {
        $check = Check::factory()->create();

        get(route('checks.edit', $check))
            ->assertStatus(403);
    });

    it('cannot update a check', function (): void {
        $check = Check::factory()->create();
        $wallet = ChurchWallet::factory()->create();
        $wallet->depositFloat('200.00'); // Add sufficient balance
        $member = Member::factory()->create();
        $expenseType = ExpenseType::factory()->create();

        $updateData = [
            'amount' => '150.00',
            'member_id' => (string) $member->id,
            'date' => '2025-06-07',
            'type' => CheckType::PAYMENT->value,
            'wallet_id' => (string) $wallet->id,
            'expense_type_id' => (string) $expenseType->id,
        ];

        $this->put(route('checks.update', $check), $updateData)
            ->assertStatus(403);
    });
});
