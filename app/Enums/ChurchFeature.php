<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum ChurchFeature: string implements HasDescription, HasLabel
{
    use EnumToArray;

    case TEST_FEATURE = 'test-feature';
    case ANOTHER_FEATURE = 'another-feature';

    public function label(): string
    {
        return match ($this) {
            self::TEST_FEATURE => 'Test Feature',
            self::ANOTHER_FEATURE => 'Another Feature',
        };
    }

    public function getLabel(): string
    {
        return $this->label();
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::TEST_FEATURE => 'This is a test feature.',
            self::ANOTHER_FEATURE => 'This is another feature.',
        };
    }
}
