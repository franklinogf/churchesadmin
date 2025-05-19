<?php

declare(strict_types=1);

namespace App\Actions\Check;

use App\Actions\Wallet\ConfirmTransactionAction;
use App\Models\Check;

final readonly class ConfirmCheckAction
{
    public function __construct(
        private ConfirmTransactionAction $confirmTransactionAction,
    ) {}

    /**
     * Handle the confirmation of a check.
     *
     * @return bool
     */
    public function handle(Check $check): bool
    {

        return $this->confirmTransactionAction->handle($check->transaction);

    }
}
