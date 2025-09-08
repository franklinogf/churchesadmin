<?php

declare(strict_types=1);

use App\Actions\Offering\DeleteOfferingAction;
use App\Models\Offering;
use Bavix\Wallet\Models\Transaction;

it('can delete an offering and its transaction', function (): void {

    Offering::factory()->create();

    $offering = Offering::latest()->first();

    $action = new DeleteOfferingAction();
    $action->handle($offering);

    // Verify offering is deleted
    expect(Offering::find($offering->id))->toBeNull()
        ->and(Transaction::find($offering->transaction_id))->toBeNull();
});
