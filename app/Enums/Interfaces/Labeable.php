<?php

declare(strict_types=1);

namespace App\Enums\Interfaces;

interface Labeable
{
    /**
     * Get the options for the enum.
     *
     * @return array<string, string>
     */
    public static function options(): array;

    /**
     * Get the label for the enum.
     *
     * @return string
     */
    public function label(): string;
}
