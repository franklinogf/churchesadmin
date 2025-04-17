<?php

namespace App\Enums;

enum WalletName:string
{
    case PRIMARY = 'primary';

    public function label():string{
        return match($this){
            self::PRIMARY => 'Primary Wallet',
        };
    }
}
