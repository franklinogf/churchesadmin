<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Expense;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::EXPENSES_MANAGE, TenantPermission::EXPENSES_DELETE);
    });

    it('can delete an expense', function (): void {
        $expense = Expense::factory()->create();

        $this->delete(route('expenses.destroy', $expense))
            ->assertRedirect(route('expenses.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot delete an expense', function (): void {
        $expense = Expense::factory()->create();

        $this->delete(route('expenses.destroy', $expense))
            ->assertStatus(403);

        $this->assertDatabaseHas('expenses', ['id' => $expense->id]);
    });
});

it('cannot delete an expense if not authenticated', function (): void {
    $expense = Expense::factory()->create();

    $this->delete(route('expenses.destroy', $expense))
        ->assertRedirect(route('login'));
});
