<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Wallet\UpdateWalletAction;
use App\Enums\FlashMessageKey;
use App\Http\Requests\Wallet\UpdateWalletCheckLayoutRequest;
use App\Http\Resources\Wallet\CheckLayoutResource;
use App\Http\Resources\Wallet\ChurchWalletResource;
use App\Models\CheckLayout;
use App\Models\ChurchWallet;
use App\Support\SelectOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class WalletCheckLayoutController extends Controller
{
    public function edit(Request $request, ChurchWallet $wallet): Response
    {
        Gate::authorize('updateCheckLayout', $wallet);

        $wallet->load('checkLayout');

        $checkLayoutId = $request->integer('layout', $wallet->checkLayout->id ?? 0);

        $checkLayout = CheckLayout::find($checkLayoutId);

        $checkLayouts = SelectOption::create(CheckLayout::all());

        return Inertia::render('wallets/check-layout', [
            'wallet' => new ChurchWalletResource($wallet),
            'checkLayouts' => $checkLayouts,
            'checkLayout' => $checkLayout ? new CheckLayoutResource($checkLayout) : null,
        ]);
    }

    public function update(UpdateWalletCheckLayoutRequest $request, ChurchWallet $wallet, UpdateWalletAction $action): RedirectResponse
    {

        $check_layout_id = $request->integer('check_layout_id');

        $action->handle($wallet, ['check_layout_id' => $check_layout_id]);

        return back()->with(FlashMessageKey::SUCCESS->value, __('flash.message.updated', ['model' => __('Wallet')]));
    }
}
