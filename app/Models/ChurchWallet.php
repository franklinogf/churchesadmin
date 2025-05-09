<?php

declare(strict_types=1);

namespace App\Models;


use Bavix\Wallet\Traits\HasWalletFloat;

use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Interfaces\Wallet;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ChurchWallet extends Model implements Wallet
{
    use SoftDeletes, HasWalletFloat;
}
