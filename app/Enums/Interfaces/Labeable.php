<?php

declare(strict_types=1);

namespace App\Enums\Interfaces;

interface Labeable
{
    /**
     * Get the label for the enum.
     *
     * @return string
     */
    public function label(): string;
}
