<?php

declare(strict_types=1);

use App\Enums\TagType;

it('has needed enums', function (): void {

    expect(TagType::names())->toBe([
        'SKILL',
        'CATEGORY',
    ]);

});

test('label return correct label', function (): void {

    expect(TagType::SKILL->label())->toBe(__('Skill'));
    expect(TagType::CATEGORY->label())->toBe(__('Category'));

});
