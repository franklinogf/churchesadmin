<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Models\Check;

describe('if user has permission', function (): void {
    beforeEach(function (): void {
        asUserWithPermission(TenantPermission::CHECKS_MANAGE, TenantPermission::CHECKS_DELETE);
    });

    it('can delete a check', function (): void {
        $check = Check::factory()->create();

        $this->delete(route('checks.destroy', $check))
            ->assertRedirect(route('checks.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('checks', ['id' => $check->id]);
    });
});

describe('if user does not have permission', function (): void {
    beforeEach(function (): void {
        asUserWithoutPermission();
    });

    it('cannot delete a check', function (): void {
        $check = Check::factory()->create();

        $this->delete(route('checks.destroy', $check))
            ->assertStatus(403);

        $this->assertDatabaseHas('checks', ['id' => $check->id]);
    });
});

it('cannot delete a check if not authenticated', function (): void {
    $check = Check::factory()->create();

    $this->delete(route('checks.destroy', $check))
        ->assertRedirect(route('login'));
});
