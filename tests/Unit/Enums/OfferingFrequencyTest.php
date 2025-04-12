<?php

declare(strict_types=1);

use App\Enums\OfferingFrequency;

it('has needed enums', function (): void {

    expect(OfferingFrequency::names())->toBe([
        'ONE_TIME',
        'WEEKLY',
        'BIWEEKLY',
        'MONTHLY',
        'BIMONTHLY',
        'QUARTERLY',
        'SEMIANNUALLY',
        'ANNUALLY',

    ]);

});

test('label return correct label', function (): void {

    expect(OfferingFrequency::ONE_TIME->label())->toBe(__('One time only'))->toBeString();
    expect(OfferingFrequency::WEEKLY->label())->toBe(__('Every week'))->toBeString();
    expect(OfferingFrequency::BIWEEKLY->label())->toBe(__('Every two weeks'))->toBeString();
    expect(OfferingFrequency::MONTHLY->label())->toBe(__('Every month'))->toBeString();
    expect(OfferingFrequency::BIMONTHLY->label())->toBe(__('Every two months'))->toBeString();
    expect(OfferingFrequency::QUARTERLY->label())->toBe(__('Every three months'))->toBeString();
    expect(OfferingFrequency::SEMIANNUALLY->label())->toBe(__('Every six months'))->toBeString();
    expect(OfferingFrequency::ANNUALLY->label())->toBe(__('Every year'))->toBeString();

});

test('options return an array', function (): void {

    expect(OfferingFrequency::options())->toBeArray();
    expect(OfferingFrequency::options())->toHaveCount(8);
    expect(OfferingFrequency::options())->toBe([
        ['value' => 'one_time', 'label' => __('One time only')],
        ['value' => 'weekly', 'label' => __('Every week')],
        ['value' => 'biweekly', 'label' => __('Every two weeks')],
        ['value' => 'monthly', 'label' => __('Every month')],
        ['value' => 'bimonthly', 'label' => __('Every two months')],
        ['value' => 'quarterly', 'label' => __('Every three months')],
        ['value' => 'semiannually', 'label' => __('Every six months')],
        ['value' => 'annually', 'label' => __('Every year')],

    ]);

});

test('frequencyInDays return correct days', function (): void {

    expect(OfferingFrequency::ONE_TIME->frequencyInDays())->toBe(0);
    expect(OfferingFrequency::WEEKLY->frequencyInDays())->toBe(7);
    expect(OfferingFrequency::BIWEEKLY->frequencyInDays())->toBe(14);
    expect(OfferingFrequency::MONTHLY->frequencyInDays())->toBe(30);
    expect(OfferingFrequency::BIMONTHLY->frequencyInDays())->toBe(60);
    expect(OfferingFrequency::QUARTERLY->frequencyInDays())->toBe(90);
    expect(OfferingFrequency::SEMIANNUALLY->frequencyInDays())->toBe(180);
    expect(OfferingFrequency::ANNUALLY->frequencyInDays())->toBe(365);

});
