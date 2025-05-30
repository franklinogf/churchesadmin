<?php

declare(strict_types=1);

use App\Enums\FollowUpType;

it('has needed enums', function (): void {
    expect(FollowUpType::names())->toBe([
        'CALL',
        'EMAIL',
        'IN_PERSON',
        'LETTER',
    ]);
});

test('label return correct label', function (): void {
    expect(FollowUpType::CALL->label())->toBe(__('enum.follow_up_type.call'))->toBeString();
    expect(FollowUpType::EMAIL->label())->toBe(__('enum.follow_up_type.email'))->toBeString();
    expect(FollowUpType::IN_PERSON->label())->toBe(__('enum.follow_up_type.in_person'))->toBeString();
    expect(FollowUpType::LETTER->label())->toBe(__('enum.follow_up_type.letter'))->toBeString();
});

test('options return an array', function (): void {
    expect(FollowUpType::options())->toBeArray();
    expect(FollowUpType::options())->toHaveCount(4);

    expect(FollowUpType::options())->toEqual([
        [
            'value' => 'call',
            'label' => __('enum.follow_up_type.call'),
        ],
        [
            'value' => 'email',
            'label' => __('enum.follow_up_type.email'),
        ],
        [
            'value' => 'in_person',
            'label' => __('enum.follow_up_type.in_person'),
        ],
        [
            'value' => 'letter',
            'label' => __('enum.follow_up_type.letter'),
        ],
    ]);
});
