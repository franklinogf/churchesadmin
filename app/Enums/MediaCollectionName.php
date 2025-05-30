<?php

declare(strict_types=1);

namespace App\Enums;

enum MediaCollectionName: string
{
    case LOGO = 'logo';
    case DEFAULT = 'default';
    case ATTACHMENT = 'attachment';
}
