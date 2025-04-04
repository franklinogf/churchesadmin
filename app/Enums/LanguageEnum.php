<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum LanguageEnum: string
{
    use EnumToArray;
    case EN = 'en';
    case ES = 'es';

}
