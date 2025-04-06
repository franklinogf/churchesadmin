<?php

declare(strict_types=1);

use App\Enums\OfferingFrequency;

it('has needed enums', function (): void {

    expect(OfferingFrequency::names())->toBe([
        'WEEKLY',
        'BIWEEKLY',
        'MONTHLY',
        'BIMONTHLY',
        'QUARTERLY',
        'SEMIANNUALLY',
        'ANNUALLY',
        'ONE_TIME',

    ]);

});

test('label return correct label', function (): void {

    expect(OfferingFrequency::WEEKLY->label())->toBe(__('Every week'))->toBeString();
    expect(OfferingFrequency::BIWEEKLY->label())->toBe(__('Every two weeks'))->toBeString();
    expect(OfferingFrequency::MONTHLY->label())->toBe(__('Every month'))->toBeString();
    expect(OfferingFrequency::BIMONTHLY->label())->toBe(__('Every two months'))->toBeString();
    expect(OfferingFrequency::QUARTERLY->label())->toBe(__('Every three months'))->toBeString();
    expect(OfferingFrequency::SEMIANNUALLY->label())->toBe(__('Every six months'))->toBeString();
    expect(OfferingFrequency::ANNUALLY->label())->toBe(__('Every year'))->toBeString();
    expect(OfferingFrequency::ONE_TIME->label())->toBe(__('One time only'))->toBeString();

});

test('options return an array', function (): void {

    expect(OfferingFrequency::options())->toBeArray();
    expect(OfferingFrequency::options())->toHaveCount(8);
    expect(OfferingFrequency::options())->toHaveKeys([
        'weekly',
        'biweekly',
        'monthly',
        'bimonthly',
        'quarterly',
        'semiannually',
        'annually',
        'one_time',
    ]);

});

test('frequencyInDays return correct days', function (): void {

    expect(OfferingFrequency::WEEKLY->frequencyInDays())->toBe(7);
    expect(OfferingFrequency::BIWEEKLY->frequencyInDays())->toBe(14);
    expect(OfferingFrequency::MONTHLY->frequencyInDays())->toBe(30);
    expect(OfferingFrequency::BIMONTHLY->frequencyInDays())->toBe(60);
    expect(OfferingFrequency::QUARTERLY->frequencyInDays())->toBe(90);
    expect(OfferingFrequency::SEMIANNUALLY->frequencyInDays())->toBe(180);
    expect(OfferingFrequency::ANNUALLY->frequencyInDays())->toBe(365);
    expect(OfferingFrequency::ONE_TIME->frequencyInDays())->toBe(0);

});
