<?php

declare(strict_types=1);

use Tests\Unit\Enums\Traits\TestBackedEnum;
use Tests\Unit\Enums\Traits\TestUnitEnum;

test('options method returns value and label keys array', function (): void {

    expect(TestBackedEnum::options())->toBeArray()->toHaveCount(3);
    expect(array_keys(TestBackedEnum::options()[0]))->toBe([
        'value',
        'label',
    ]);

    expect(TestUnitEnum::options())->toBeArray()->toHaveCount(3);
    expect(array_keys(TestUnitEnum::options()[0]))->toBe([
        'value',
        'label',
    ]);

});

test('asOption method returns value and label keys', function (): void {
    expect(TestBackedEnum::USER->asOption())->toBe([
        'value' => 'user',
        'label' => 'User',
    ]);

    expect(TestUnitEnum::TWO->asOption())->toBe([
        'value' => 'TWO',
        'label' => 'Two',
    ]);
});
