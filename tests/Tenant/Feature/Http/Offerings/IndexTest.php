<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Offering;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('offerings.index'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::OFFERINGS_MANAGE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('offerings.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('offerings/index')
                ->has('offerings')
                ->has('date')
            );
    });

    it('displays offerings in the list', function (): void {
        $offerings = Offering::factory()->count(3)->create();

        get(route('offerings.index'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('offerings/index')
                ->has('offerings')
            );
    });

    it('can filter offerings by date', function (): void {
        $offering1 = Offering::factory()->create(['date' => '2025-06-01']);
        $offering2 = Offering::factory()->create(['date' => '2025-06-02']);

        get(route('offerings.index', ['date' => '2025-06-01']))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('offerings/index')
                ->where('date', '2025-06-01')
                ->has('offerings')
            );
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the offerings index', function (): void {
        get(route('offerings.index'))
            ->assertStatus(403);
    });
});
