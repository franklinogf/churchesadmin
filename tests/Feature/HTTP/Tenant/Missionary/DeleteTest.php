<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Missionary;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted if user has permission', function (): void {
    $missionary = Missionary::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_MISSIONARIES, TenantPermission::DELETE_MISSIONARIES)
        ->from(route('missionaries.index'))
        ->delete(route('missionaries.destroy', ['missionary' => $missionary]))
        ->assertRedirect(route('missionaries.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('missionaries', 1);

    expect(Missionary::all()->count())->toBe(0)
        ->and(Missionary::withTrashed()->count())->toBe(1);

});

it('cannot be deleted if user does not have permission', function (): void {
    $missionary = Missionary::factory()->create()->fresh();

    asUserWithPermission(TenantPermission::MANAGE_MISSIONARIES)
        ->from(route('missionaries.index'))
        ->delete(route('missionaries.destroy', ['missionary' => $missionary]))
        ->assertForbidden();

    assertDatabaseCount('missionaries', 1);

    expect(Missionary::all()->count())->toBe(1);

});
