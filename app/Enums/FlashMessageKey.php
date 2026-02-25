<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;

enum FlashMessageKey: string
{
    use EnumToArray;

    case SUCCESS = 'success';
    case ERROR = 'error';
    case MESSAGE = 'message';
}
