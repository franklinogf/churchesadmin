<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Visit;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

it('cannot be rendered if not authenticated', function (): void {
    $visit = Visit::factory()->create();

    get(route('visits.edit', $visit))
        ->assertRedirect(route('login'));
});

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::VISITS_MANAGE, TenantPermission::VISITS_UPDATE);
    });

    it('can be rendered if authenticated', function (): void {
        $visit = Visit::factory()->create();

        get(route('visits.edit', $visit))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page): Assert => $page
                ->component('main/visits/edit')
                ->has('visit')
                ->where('visit.id', $visit->id)
            );
    });

    it('can update a visit', function (): void {
        $visit = Visit::factory()->create([
            'name' => 'Original',
            'last_name' => 'Name',
        ]);

        $updateData = [
            'name' => 'Updated',
            'last_name' => 'Name',
            'phone' => '+19293394305',
            'first_visit_date' => '2025-06-07',
        ];

        $this->from(route('visits.edit', $visit))
            ->put(route('visits.update', $visit), $updateData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('visits.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('visits', [
            'id' => $visit->id,
            'name' => 'Updated',
            'last_name' => 'Name',
        ]);
    });

    it('can update a visit to remove phone', function (): void {
        $visit = Visit::factory()->create([
            'name' => 'Original',
            'last_name' => 'Name',
            'phone' => '+19293394305',
        ]);

        $updateData = [
            'name' => 'Updated',
            'last_name' => 'Name',
            'phone' => null,
            'first_visit_date' => '2025-06-07',
        ];

        $this->from(route('visits.edit', $visit))
            ->put(route('visits.update', $visit), $updateData)
            ->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('visits.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('visits', [
            'id' => $visit->id,
            'name' => 'Updated',
            'last_name' => 'Name',
            'phone' => null,
        ]);
    });

    it('validates required fields on update', function (): void {
        $visit = Visit::factory()->create();

        $this->from(route('visits.edit', $visit))
            ->put(route('visits.update', $visit), [
                'name' => '',
                'last_name' => '',
            ])
            ->assertSessionHasErrors(['name', 'last_name']);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot access the edit visit form', function (): void {
        $visit = Visit::factory()->create();

        get(route('visits.edit', $visit))
            ->assertStatus(403);
    });

    it('cannot update a visit', function (): void {
        $visit = Visit::factory()->create();

        $updateData = [
            'name' => 'Updated',
            'last_name' => 'Name',
            'phone' => '+19293394305',
            'first_visit_date' => '2025-06-07',
        ];

        $this->put(route('visits.update', $visit), $updateData)
            ->assertStatus(403);
    });
});
