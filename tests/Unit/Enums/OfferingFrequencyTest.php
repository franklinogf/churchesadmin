<?php

declare(strict_types=1);

use App\Enums\OfferingFrequency;

it('has needed enums', function (): void {

    expect(OfferingFrequency::names())->toBe([
        'ONETIME',
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

    expect(OfferingFrequency::ONETIME->label())->toBe(__('enum.offering_frequency.one_time'))
        ->and(OfferingFrequency::WEEKLY->label())->toBe(__('enum.offering_frequency.weekly'))
        ->and(OfferingFrequency::BIWEEKLY->label())->toBe(__('enum.offering_frequency.bi_weekly'))
        ->and(OfferingFrequency::MONTHLY->label())->toBe(__('enum.offering_frequency.monthly'))
        ->and(OfferingFrequency::BIMONTHLY->label())->toBe(__('enum.offering_frequency.bi_monthly'))
        ->and(OfferingFrequency::QUARTERLY->label())->toBe(__('enum.offering_frequency.quarterly'))
        ->and(OfferingFrequency::SEMIANNUALLY->label())->toBe(__('enum.offering_frequency.semi_annually'))
        ->and(OfferingFrequency::ANNUALLY->label())->toBe(__('enum.offering_frequency.annually'));

});

test('options return an array', function (): void {

    expect(OfferingFrequency::options())
        ->toBe([
            ['value' => 'one_time', 'label' => __('enum.offering_frequency.one_time')],
            ['value' => 'weekly', 'label' => __('enum.offering_frequency.weekly')],
            ['value' => 'bi_weekly', 'label' => __('enum.offering_frequency.bi_weekly')],
            ['value' => 'monthly', 'label' => __('enum.offering_frequency.monthly')],
            ['value' => 'bi_monthly', 'label' => __('enum.offering_frequency.bi_monthly')],
            ['value' => 'quarterly', 'label' => __('enum.offering_frequency.quarterly')],
            ['value' => 'semi_annually', 'label' => __('enum.offering_frequency.semi_annually')],
            ['value' => 'annually', 'label' => __('enum.offering_frequency.annually')],
        ]);

});

test('frequencyInDays return correct days', function (): void {

    expect(OfferingFrequency::ONETIME->frequencyInDays())->toBe(0);
    expect(OfferingFrequency::WEEKLY->frequencyInDays())->toBe(7);
    expect(OfferingFrequency::BIWEEKLY->frequencyInDays())->toBe(14);
    expect(OfferingFrequency::MONTHLY->frequencyInDays())->toBe(30);
    expect(OfferingFrequency::BIMONTHLY->frequencyInDays())->toBe(60);
    expect(OfferingFrequency::QUARTERLY->frequencyInDays())->toBe(90);
    expect(OfferingFrequency::SEMIANNUALLY->frequencyInDays())->toBe(180);
    expect(OfferingFrequency::ANNUALLY->frequencyInDays())->toBe(365);

});
