<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Offering;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::OFFERINGS_MANAGE, TenantPermission::OFFERINGS_DELETE);
    });

    it('can delete an offering', function (): void {
        $offering = Offering::factory()->create();

        $this->delete(route('offerings.destroy', $offering))
            ->assertRedirect(route('offerings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('offerings', ['id' => $offering->id]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot delete an offering', function (): void {
        $offering = Offering::factory()->create();

        $this->delete(route('offerings.destroy', $offering))
            ->assertStatus(403);

        $this->assertDatabaseHas('offerings', ['id' => $offering->id]);
    });
});

it('cannot delete an offering if not authenticated', function (): void {
    $offering = Offering::factory()->create();

    $this->delete(route('offerings.destroy', $offering))
        ->assertRedirect(route('login'));
});
