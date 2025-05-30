<?php

declare(strict_types=1);

namespace Tests\Feature\HTTP\Tenant\Visit\Actions;

use App\Actions\Visit\RestoreVisitAction;
use App\Enums\TenantPermission;
use App\Models\Visit;

it('can restore a soft deleted visit', function (): void {
    asUserWithPermission(TenantPermission::VISITS_RESTORE);

    $visit = Visit::factory()->create();
    $visit->delete(); // Soft delete

    expect(Visit::count())->toBe(0)
        ->and(Visit::withTrashed()->count())->toBe(1);

    $action = new RestoreVisitAction();
    $action->handle($visit);

    expect(Visit::count())->toBe(1)
        ->and(Visit::withTrashed()->count())->toBe(1)
        ->and($visit->refresh()->deleted_at)->toBeNull();
});
