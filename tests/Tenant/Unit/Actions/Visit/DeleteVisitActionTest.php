<?php

declare(strict_types=1);

namespace Tests\Feature\HTTP\Tenant\Visit\Actions;

use App\Actions\Visit\DeleteVisitAction;
use App\Models\Visit;

it('can soft delete a visit', function (): void {

    $visit = Visit::factory()->create();
    expect(Visit::count())->toBe(1);

    $action = new DeleteVisitAction();
    $action->handle($visit);

    expect(Visit::count())->toBe(0)
        ->and(Visit::withTrashed()->count())->toBe(1)
        ->and($visit->refresh()->deleted_at)->not->toBeNull();
});
