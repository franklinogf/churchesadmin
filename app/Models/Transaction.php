<?php

declare(strict_types=1);

namespace App\Models;

use Bavix\Wallet\Models\Transaction as BaseTransaction;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

final class Transaction extends BaseTransaction
{
    use CentralConnection;
}
