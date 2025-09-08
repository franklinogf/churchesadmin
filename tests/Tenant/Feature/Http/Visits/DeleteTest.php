<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Visit;

use function Pest\Laravel\assertSoftDeleted;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::VISITS_MANAGE, TenantPermission::VISITS_DELETE);
    });

    it('can delete a visit', function (): void {
        $visit = Visit::factory()->create();

        $this->delete(route('visits.destroy', $visit))
            ->assertRedirect(route('visits.index'))
            ->assertSessionHas('success');

        assertSoftDeleted('visits', ['id' => $visit->id]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot delete a visit', function (): void {
        $visit = Visit::factory()->create();

        $this->delete(route('visits.destroy', $visit))
            ->assertStatus(403);

        $this->assertDatabaseHas('visits', ['id' => $visit->id]);
    });
});

it('cannot delete a visit if not authenticated', function (): void {
    $visit = Visit::factory()->create();

    $this->delete(route('visits.destroy', $visit))
        ->assertRedirect(route('login'));
});
