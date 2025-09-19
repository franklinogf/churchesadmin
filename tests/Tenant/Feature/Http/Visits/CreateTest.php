<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    get(route('visits.create'))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::VISITS_MANAGE, TenantPermission::VISITS_CREATE);
    });

    it('can be rendered if authenticated', function (): void {
        get(route('visits.create'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('main/visits/create')
            );
    });

    it('can store a visit', function (): void {
        $visitData = [
            'name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+19293394305',
            'first_visit_date' => '2025-06-07',
        ];

        $this->from(route('visits.create'))
            ->post(route('visits.store'), $visitData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('visits.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('visits', [
            'name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+19293394305',
        ]);
    });

    it('can store a visit without phone', function (): void {
        $visitData = [
            'name' => 'Jane',
            'last_name' => 'Smith',
            'first_visit_date' => '2025-06-07',
        ];

        $this->from(route('visits.create'))
            ->post(route('visits.store'), $visitData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('visits.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('visits', [
            'name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => null,
        ]);
    });

    it('validates required fields', function (): void {
        $this->from(route('visits.create'))
            ->post(route('visits.store'), [])
            ->assertSessionHasErrors(['name', 'last_name']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the create visit form', function (): void {
        get(route('visits.create'))
            ->assertStatus(403);
    });

    it('cannot store a visit', function (): void {
        $visitData = [
            'name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+19293394305',
            'first_visit_date' => '2025-06-07',
        ];

        $this->post(route('visits.store'), $visitData)
            ->assertStatus(403);
    });
});
