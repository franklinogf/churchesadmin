<?php

declare(strict_types=1);

namespace App\Enums;

enum WalletName: string
{
    case PRIMARY = 'primary';

    public function label(): string
    {
        return __("enum.wallet_name.{$this->value}");
    }
}
