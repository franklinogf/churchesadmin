<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Actions\Wallet\ConfirmTransaction;
use App\Models\Check;

final readonly class ConfirmCheckAction
{
    public function __construct(
        private ConfirmTransaction $confirmTransaction,
    ) {}

    /**
     * Handle the confirmation of a check.
     *
     * @return bool
     */
    public function handle(Check $check): bool
    {

        return $this->confirmTransaction->handle($check->transaction);

    }
}
