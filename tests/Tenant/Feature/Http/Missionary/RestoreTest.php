<?php

declare(strict_types=1);

use App\Enums\FlashMessageKey;
use App\Enums\TenantPermission;
use App\Models\Missionary;

use function Pest\Laravel\assertDatabaseCount;

it('can be restored if user has permission', function (): void {
    $missionary = Missionary::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE, TenantPermission::MISSIONARIES_RESTORE)
        ->put(route('missionaries.restore', ['missionary' => $missionary]))
        ->assertRedirect(route('missionaries.index'))
        ->assertSessionHas(FlashMessageKey::SUCCESS->value);

    assertDatabaseCount('missionaries', 1);

    expect(Missionary::all()->count())->toBe(1)
        ->and(Missionary::withTrashed()->count())->toBe(1);

});

it('cannot be restored if user does not have permission', function (): void {
    $missionary = Missionary::factory()->trashed()->create()->fresh();

    asUserWithPermission(TenantPermission::MISSIONARIES_MANAGE)
        ->put(route('missionaries.restore', ['missionary' => $missionary]))
        ->assertForbidden();

    assertDatabaseCount('missionaries', 1);

    expect(Missionary::all()->count())->toBe(0)
        ->and(Missionary::withTrashed()->count())->toBe(1);

});
