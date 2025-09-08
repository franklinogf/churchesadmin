<?php

declare(strict_types=1);

namespace App\Enums;

enum PdfOrientation: string
{
    case PORTRAIT = 'portrait';
    case LANDSCAPE = 'landscape';
}
