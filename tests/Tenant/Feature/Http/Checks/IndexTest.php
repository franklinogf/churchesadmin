<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Check;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('checks.index'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CHECKS_MANAGE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('checks.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('checks/index')
                ->has('unconfirmedChecks')
                ->has('nextCheckNumber')
            );
    });

    it('displays checks in the list', function (): void {
        $unconfirmedCheck = Check::factory()->create();
        Check::factory()->confirmed()->create();

        get(route('checks.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('checks/index')
                ->has('unconfirmedChecks', 1)
                ->has('unconfirmedChecks.0', fn (Assert $page): Assert => $page
                    ->where('id', $unconfirmedCheck->id)
                    ->etc()
                )
            );
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the checks index', function (): void {
        get(route('checks.index'))
            ->assertStatus(403);
    });
});
