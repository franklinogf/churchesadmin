<?php

declare(strict_types=1);

use App\Enums\TagType;

it('has needed enums', function (): void {

    expect(TagType::names())->toBe([
        'SKILL',
    ]);

});
