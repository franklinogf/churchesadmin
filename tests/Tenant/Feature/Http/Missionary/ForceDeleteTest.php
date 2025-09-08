<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Missionary;

use function Pest\Laravel\assertDatabaseCount;

it('can be deleted permanently if user has permission', function (): void {
    $missionary = Missionary::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE, TenantPermission::MISSIONARIES_FORCE_DELETE)
        ->from(route('missionaries.index'))
        ->delete(route('missionaries.forceDelete', ['missionary' => $missionary]))
        ->assertRedirect(route('missionaries.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('missionaries', 0);

    expect(Missionary::all()->count())->toBe(0)
        ->and(Missionary::withTrashed()->count())->toBe(0);

});

it('cannot be deleted permanently if user does not have permission', function (): void {
    $missionary = Missionary::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE)
        ->from(route('missionaries.index'))
        ->delete(route('missionaries.forceDelete', ['missionary' => $missionary]))
        ->assertForbidden();

    assertDatabaseCount('missionaries', 1);

    expect(Missionary::all()->count())->toBe(0)
        ->and(Missionary::withTrashed()->count())->toBe(1);

});
