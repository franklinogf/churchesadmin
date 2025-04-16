<?php

declare(strict_types=1);

use App\Enums\CivilStatus;

it('has needed enums', function (): void {

    expect(CivilStatus::names())->toBe([
        'SINGLE',
        'MARRIED',
        'DIVORCED',
        'WIDOWED',
        'SEPARATED',
    ]);

});

test('label return correct label', function (): void {

    expect(CivilStatus::SINGLE->label())->toBe(__('Single'))->toBeString();
    expect(CivilStatus::MARRIED->label())->toBe(__('Married'))->toBeString();
    expect(CivilStatus::DIVORCED->label())->toBe(__('Divorced'))->toBeString();
    expect(CivilStatus::WIDOWED->label())->toBe(__('Widowed'))->toBeString();
    expect(CivilStatus::SEPARATED->label())->toBe(__('Separated'))->toBeString();

});

test('options return an array', function (): void {

    expect(CivilStatus::options())->toBeArray();
    expect(CivilStatus::options())->toHaveCount(5);
    expect(CivilStatus::options())->toBe(
        [
            [
                'value' => 'single',
                'label' => __('Single'),
            ],
            [
                'value' => 'married',
                'label' => __('Married'),
            ],
            [
                'value' => 'divorced',
                'label' => __('Divorced'),
            ],
            [
                'value' => 'widowed',
                'label' => __('Widowed'),
            ],
            [
                'value' => 'separated',
                'label' => __('Separated'),
            ],
        ]
    );

});
