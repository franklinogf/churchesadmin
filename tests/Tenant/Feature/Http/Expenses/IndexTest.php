<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Expense;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('expenses.index'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::EXPENSES_MANAGE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('expenses.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('expenses/index')
                ->has('expenses')
            );
    });

    it('displays expenses in the list', function (): void {
        $expenses = Expense::factory()->count(3)->create();
        // Get the expenses in the same order as they will be returned by the controller
        $expectedExpenses = $expenses->sortByDesc('date')->values();

        get(route('expenses.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('expenses/index')
                ->has('expenses', 3)
                ->has('expenses.0', fn (Assert $page): Assert => $page
                    ->where('id', $expectedExpenses[0]->id)
                    ->etc()
                )
            );
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the expenses index', function (): void {
        get(route('expenses.index'))
            ->assertStatus(403);
    });
});
