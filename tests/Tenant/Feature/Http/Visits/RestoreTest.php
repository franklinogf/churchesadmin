<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Visit;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::VISITS_MANAGE, TenantPermission::VISITS_RESTORE);
    });

    it('can restore a visit', function (): void {
        $visit = Visit::factory()->create();
        $visit->delete(); // First soft delete it

        $this->put(route('visits.restore', $visit))
            ->assertRedirect(route('visits.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('visits', [
            'id' => $visit->id,
            'deleted_at' => null,
        ]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot restore a visit', function (): void {
        $visit = Visit::factory()->create();
        $visit->delete(); // First soft delete it

        $this->put(route('visits.restore', $visit))
            ->assertStatus(403);
    });
});

it('cannot restore a visit if not authenticated', function (): void {
    $visit = Visit::factory()->create();
    $visit->delete(); // First soft delete it

    $this->put(route('visits.restore', $visit))
        ->assertRedirect(route('login'));
});
