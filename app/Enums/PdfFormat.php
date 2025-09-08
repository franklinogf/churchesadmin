<?php

declare(strict_types=1);

namespace App\Enums;

enum PdfFormat: string
{
    case A0 = 'a0';
    case A1 = 'a1';
    case A2 = 'a2';
    case A3 = 'a3';
    case A4 = 'a4';
    case A5 = 'a5';
    case A6 = 'a6';
    case LETTER = 'letter';
    case LEGAL = 'legal';
    case TABLOID = 'tabloid';
}
