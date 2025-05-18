<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Wallet\UpdateWalletAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\Wallet\UpdateWalletCheckLayoutRequest;
use App\Models\ChurchWallet;
use Illuminate\Http\RedirectResponse;

final class ChangeWalletLayoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateWalletCheckLayoutRequest $request, ChurchWallet $wallet, UpdateWalletAction $action): RedirectResponse
    {
        $check_layout_id = $request->integer('check_layout_id');

        $action->handle($wallet, ['check_layout_id' => $check_layout_id]);

        return back()->with(FlashMessageKey::SUCCESS->value, __('flash.message.updated', ['model' => __('Wallet')]));
    }
}
