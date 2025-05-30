<?php

declare(strict_types=1);

namespace Tests\Feature\HTTP\Tenant\Visit\Actions;

use App\Actions\Visit\DeleteFollowUpAction;
use App\Models\FollowUp;

it('can delete a follow-up', function (): void {

    $followUp = FollowUp::factory()->create();

    expect(FollowUp::count())->toBe(1);

    $action = new DeleteFollowUpAction();
    $action->handle($followUp);

    expect(FollowUp::count())->toBe(0)
        ->and(FollowUp::withTrashed()->count())->toBe(1)
        ->and($followUp->refresh()->deleted_at)->not->toBeNull();
});
