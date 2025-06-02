<?php

declare(strict_types=1);

use App\Actions\Missionary\DeleteMissionaryAction;
use App\Models\Missionary;

it('can soft delete a missionary', function (): void {
    $missionary = Missionary::factory()->create([
        'name' => 'John',
        'last_name' => 'Missionary',
        'email' => 'john.missionary@example.com',
    ]);

    $missionaryId = $missionary->id;

    $action = new DeleteMissionaryAction();
    $action->handle($missionary);

    // Missionary should be soft deleted
    expect(Missionary::find($missionaryId))->toBeNull()
        ->and(Missionary::withTrashed()->find($missionaryId))->not->toBeNull()
        ->and(Missionary::withTrashed()->find($missionaryId)->deleted_at)->not->toBeNull();
});

it('can delete missionary with address', function (): void {
    $missionary = Missionary::factory()->hasAddress()->create();
    $missionaryId = $missionary->id;

    $action = new DeleteMissionaryAction();
    $action->handle($missionary);

    // Missionary should be soft deleted
    expect(Missionary::find($missionaryId))->toBeNull()
        ->and(Missionary::withTrashed()->find($missionaryId))->not->toBeNull();

    // Address should still exist
    expect($missionary->fresh(['address'])->address)->not->toBeNull();
});
