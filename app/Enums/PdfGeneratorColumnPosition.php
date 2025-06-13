<?php

declare(strict_types=1);

namespace App\Enums;

enum PdfGeneratorColumnPosition: string
{
    case LEFT = 'left';
    case CENTER = 'center';
    case RIGHT = 'right';
}
