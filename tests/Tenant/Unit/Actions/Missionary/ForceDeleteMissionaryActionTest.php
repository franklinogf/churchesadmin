<?php

declare(strict_types=1);

use App\Actions\Missionary\ForceDeleteMissionaryAction;
use App\Models\Address;
use App\Models\Missionary;

it('can permanently delete a missionary', function (): void {
    $missionary = Missionary::factory()->create([
        'name' => 'John',
        'last_name' => 'Missionary',
        'email' => 'john.missionary@example.com',
    ]);

    $missionaryId = $missionary->id;

    $action = new ForceDeleteMissionaryAction();
    $action->handle($missionary);

    // Missionary should be permanently deleted
    expect(Missionary::find($missionaryId))->toBeNull()
        ->and(Missionary::withTrashed()->find($missionaryId))->toBeNull();
});

it('can permanently delete a soft deleted missionary', function (): void {
    $missionary = Missionary::factory()->create();
    $missionaryId = $missionary->id;

    // First soft delete
    $missionary->delete();

    expect(Missionary::find($missionaryId))->toBeNull()
        ->and(Missionary::withTrashed()->find($missionaryId))->not->toBeNull();

    // Now force delete
    $trashedMissionary = Missionary::withTrashed()->find($missionaryId);
    $action = new ForceDeleteMissionaryAction();
    $action->handle($trashedMissionary);

    // Missionary should be permanently deleted
    expect(Missionary::find($missionaryId))->toBeNull()
        ->and(Missionary::withTrashed()->find($missionaryId))->toBeNull();
});

it('can permanently delete missionary with address', function (): void {
    $missionary = Missionary::factory()->hasAddress()->create();
    $missionaryId = $missionary->id;
    $addressId = $missionary->address->id;

    $action = new ForceDeleteMissionaryAction();
    $action->handle($missionary);

    // Missionary should be permanently deleted
    expect(Missionary::find($missionaryId))->toBeNull()
        ->and(Missionary::withTrashed()->find($missionaryId))->toBeNull();

    // Address should be deleted through cascade
    expect(Address::find($addressId))->toBeNull();
});
