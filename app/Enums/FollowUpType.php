<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\HasOptions;

enum FollowUpType: string
{
    use EnumToArray, HasOptions;
    case CALL = 'call';
    case EMAIL = 'email';
    case IN_PERSON = 'in_person';
    case LETTER = 'letter';

    public function label(): string
    {
        return match ($this) {
            self::CALL => __('enum.follow_up_type.call'),
            self::EMAIL => __('enum.follow_up_type.email'),
            self::IN_PERSON => __('enum.follow_up_type.in_person'),
            self::LETTER => __('enum.follow_up_type.letter'),
        };
    }
}
