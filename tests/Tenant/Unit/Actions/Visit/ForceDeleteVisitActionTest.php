<?php

declare(strict_types=1);

namespace Tests\Feature\HTTP\Tenant\Visit\Actions;

use App\Actions\Visit\ForceDeleteVisitAction;
use App\Models\Visit;

it('can force delete a visit', function (): void {

    $visit = Visit::factory()->create();
    $visit->delete(); // Soft delete first

    expect(Visit::count())->toBe(0)
        ->and(Visit::withTrashed()->count())->toBe(1);

    $action = new ForceDeleteVisitAction();
    $action->handle($visit);

    expect(Visit::count())->toBe(0)
        ->and(Visit::withTrashed()->count())->toBe(0);
});
