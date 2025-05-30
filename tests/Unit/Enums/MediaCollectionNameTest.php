<?php

declare(strict_types=1);

use App\Enums\MediaCollectionName;

it('has needed enums', function (): void {
    expect(MediaCollectionName::cases())->toHaveCount(3);
    expect(MediaCollectionName::LOGO->value)->toBe('logo');
    expect(MediaCollectionName::DEFAULT->value)->toBe('default');
    expect(MediaCollectionName::ATTACHMENT->value)->toBe('attachment');
});
