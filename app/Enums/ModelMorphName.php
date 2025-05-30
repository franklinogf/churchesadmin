<?php

declare(strict_types=1);

namespace App\Enums;

enum ModelMorphName: string
{
    case MEMBER = 'member';
    case MISSIONARY = 'missionary';
    case USER = 'user';
    case CHURCH = 'church';
    case CHURCH_WALLET = 'church_wallet';
    case OFFERING_TYPE = 'offering_type';
    case CHECK_LAYOUT = 'check_layout';
    case EMAIL = 'email';
    case VISIT = 'visit';

    public function label(): string
    {
        return match ($this) {
            self::MEMBER => __('enum.model_morph_name.member'),
            self::MISSIONARY => __('enum.model_morph_name.missionary'),
            self::USER => __('enum.model_morph_name.user'),
            self::CHURCH => __('enum.model_morph_name.church'),
            self::CHURCH_WALLET => __('enum.model_morph_name.church_wallet'),
            self::OFFERING_TYPE => __('enum.model_morph_name.offering_type'),
            self::CHECK_LAYOUT => __('enum.model_morph_name.check_layout'),
            self::EMAIL => __('enum.model_morph_name.email'),
            self::VISIT => __('enum.model_morph_name.visit'),
        };
    }
}
