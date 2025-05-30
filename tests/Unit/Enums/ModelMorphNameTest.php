<?php

declare(strict_types=1);

use App\Enums\ModelMorphName;

it('has needed enums', function (): void {
    expect(ModelMorphName::cases())->toHaveCount(9);
    expect(ModelMorphName::MEMBER->value)->toBe('member');
    expect(ModelMorphName::MISSIONARY->value)->toBe('missionary');
    expect(ModelMorphName::USER->value)->toBe('user');
    expect(ModelMorphName::CHURCH->value)->toBe('church');
    expect(ModelMorphName::CHURCH_WALLET->value)->toBe('church_wallet');
    expect(ModelMorphName::OFFERING_TYPE->value)->toBe('offering_type');
    expect(ModelMorphName::CHECK_LAYOUT->value)->toBe('check_layout');
    expect(ModelMorphName::EMAIL->value)->toBe('email');
    expect(ModelMorphName::VISIT->value)->toBe('visit');
});

test('label return correct label', function (): void {
    expect(ModelMorphName::MEMBER->label())->toBe(__('enum.model_morph_name.member'))->toBeString();
    expect(ModelMorphName::MISSIONARY->label())->toBe(__('enum.model_morph_name.missionary'))->toBeString();
    expect(ModelMorphName::USER->label())->toBe(__('enum.model_morph_name.user'))->toBeString();
    expect(ModelMorphName::CHURCH->label())->toBe(__('enum.model_morph_name.church'))->toBeString();
    expect(ModelMorphName::CHURCH_WALLET->label())->toBe(__('enum.model_morph_name.church_wallet'))->toBeString();
    expect(ModelMorphName::OFFERING_TYPE->label())->toBe(__('enum.model_morph_name.offering_type'))->toBeString();
    expect(ModelMorphName::CHECK_LAYOUT->label())->toBe(__('enum.model_morph_name.check_layout'))->toBeString();
    expect(ModelMorphName::EMAIL->label())->toBe(__('enum.model_morph_name.email'))->toBeString();
    expect(ModelMorphName::VISIT->label())->toBe(__('enum.model_morph_name.visit'))->toBeString();
});
