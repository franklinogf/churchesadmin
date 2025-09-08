<?php

declare(strict_types=1);

use App\Actions\Missionary\RestoreMissionaryAction;
use App\Models\Missionary;

it('can restore a soft deleted missionary', function (): void {
    $missionary = Missionary::factory()->create([
        'name' => 'John',
        'last_name' => 'Missionary',
        'email' => 'john.missionary@example.com',
    ]);

    $missionaryId = $missionary->id;

    // First soft delete the missionary
    $missionary->delete();

    expect(Missionary::find($missionaryId))->toBeNull()
        ->and(Missionary::withTrashed()->find($missionaryId))->not->toBeNull();

    // Now restore it
    $trashedMissionary = Missionary::withTrashed()->find($missionaryId);
    $action = new RestoreMissionaryAction();
    $action->handle($trashedMissionary);

    // Missionary should be restored
    expect(Missionary::find($missionaryId))->not->toBeNull()
        ->and(Missionary::find($missionaryId)->deleted_at)->toBeNull()
        ->and(Missionary::find($missionaryId)->name)->toBe('John')
        ->and(Missionary::find($missionaryId)->last_name)->toBe('Missionary');
});

it('can restore missionary with address intact', function (): void {
    $missionary = Missionary::factory()->hasAddress()->create();
    $missionaryId = $missionary->id;
    $originalAddressId = $missionary->address->id;

    // Soft delete the missionary
    $missionary->delete();

    expect(Missionary::find($missionaryId))->toBeNull();

    // Restore the missionary
    $trashedMissionary = Missionary::withTrashed()->find($missionaryId);
    $action = new RestoreMissionaryAction();
    $action->handle($trashedMissionary);

    $restoredMissionary = Missionary::find($missionaryId);

    // Missionary and address should be restored
    expect($restoredMissionary)->not->toBeNull()
        ->and($restoredMissionary->address)->not->toBeNull()
        ->and($restoredMissionary->address->id)->toBe($originalAddressId);
});
