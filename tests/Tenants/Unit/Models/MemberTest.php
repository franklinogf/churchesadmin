<?php

declare(strict_types=1);

use App\Models\Member;

test('to array', function (): void {
    $member = Member::factory()->create()->fresh();

    expect(array_keys($member->toArray()))->toBe([
        'id',
        'name',
        'last_name',
        'email',
        'phone',
        'gender',
        'dob',
        'civil_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ]);
});
