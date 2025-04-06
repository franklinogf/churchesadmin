<?php

declare(strict_types=1);

use Tests\Unit\Enums\Traits\TestBackedEnum;
use Tests\Unit\Enums\Traits\TestUnitEnum;

test('values method returns case values', function (): void {
    // Backed enum should return the case values
    expect(TestBackedEnum::values())->toBe([
        'admin',
        'user',
        'guest',
    ]);

    // Non-backed enum values will be empty
    expect(TestUnitEnum::values())->toBe([]);

});

test('names method returns case names', function (): void {
    // Both enum types should return names
    expect(TestBackedEnum::names())->toBe([
        'ADMIN',
        'USER',
        'GUEST',
    ]);

    expect(TestUnitEnum::names())->toBe([
        'ONE',
        'TWO',
        'THREE',
    ]);
});
