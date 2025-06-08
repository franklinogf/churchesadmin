<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Visit;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('visits.index'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::VISITS_MANAGE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('visits.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('main/visits/index')
                ->has('visits')
            );
    });

    it('displays visits in the list', function (): void {
        $visits = Visit::factory()->count(3)->create();

        get(route('visits.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('main/visits/index')
                ->has('visits', 3)
                ->has('visits.0', fn (Assert $page): Assert => $page
                    ->where('id', $visits[0]->id)
                    ->etc()
                )
            );
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the visits index', function (): void {
        get(route('visits.index'))
            ->assertStatus(403);
    });
});
