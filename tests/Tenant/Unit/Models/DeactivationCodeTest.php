<?php

declare(strict_types=1);

use App\Models\DeactivationCode;

test('to array', function (): void {
    $deactivationCode = DeactivationCode::factory()->create()->fresh();

    expect(array_keys($deactivationCode->toArray()))->toBe([
        'id',
        'name',
        'created_at',
        'updated_at',
    ]);
});

it('can have members', function (): void {
    $deactivationCode = DeactivationCode::factory()->create();
    $members = App\Models\Member::factory()->count(3)->inactive()->create([
        'deactivation_code_id' => $deactivationCode->id,
    ]);

    expect($deactivationCode->members)->toHaveCount(3);
    expect($deactivationCode->members->pluck('id')->all())->toEqualCanonicalizing($members->pluck('id')->all());
});
