<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Visit;

use function Pest\Laravel\assertDatabaseMissing;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::VISITS_MANAGE, TenantPermission::VISITS_FORCE_DELETE);
    });

    it('can force delete a visit', function (): void {
        $visit = Visit::factory()->create();
        $visit->delete(); // First soft delete it

        $this->delete(route('visits.forceDelete', $visit))
            ->assertRedirect(route('visits.index'))
            ->assertSessionHas('success');

        assertDatabaseMissing('visits', ['id' => $visit->id]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot force delete a visit', function (): void {
        $visit = Visit::factory()->create();
        $visit->delete(); // First soft delete it

        $this->delete(route('visits.forceDelete', $visit))
            ->assertStatus(403);
    });
});

it('cannot force delete a visit if not authenticated', function (): void {
    $visit = Visit::factory()->create();
    $visit->delete(); // First soft delete it

    $this->delete(route('visits.forceDelete', $visit))
        ->assertRedirect(route('login'));
});
