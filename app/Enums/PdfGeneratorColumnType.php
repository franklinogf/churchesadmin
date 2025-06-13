<?php

declare(strict_types=1);

namespace App\Enums;

enum PdfGeneratorColumnType: string
{
    case ENUM = 'enum';
    case TEXT = 'text';
    case CURRENCY = 'currency';
    case DATE = 'date';
    case DATE_TIME = 'datetime';
}
