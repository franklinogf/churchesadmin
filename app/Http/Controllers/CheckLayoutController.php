<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\CheckLayoutField;
use App\Enums\FlashMessageKey;
use App\Http\Requests\Check\StoreCheckLayoutRequest;
use App\Http\Requests\Check\UpdateCheckLayoutRequest;
use App\Http\Resources\Wallet\CheckLayoutResource;
use App\Http\Resources\Wallet\ChurchWalletResource;
use App\Models\CheckLayout;
use App\Models\ChurchWallet;
use App\Support\SelectOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class CheckLayoutController extends Controller
{
    public function store(StoreCheckLayoutRequest $request): RedirectResponse
    {
        $wallet_id = $request->integer('wallet_id');

        $checkLayoutId = DB::transaction(function () use ($request, $wallet_id) {
            $checkLayout = CheckLayout::create([
                'name' => $request->string('name'),
                'width' => $request->integer('width'),
                'height' => $request->integer('height'),
                'fields' => CheckLayoutField::initialLayout(),
            ]);

            $checkLayout->addMediaFromRequest('image')->toMediaCollection();
            $wallet = ChurchWallet::find($wallet_id);
            if ($wallet) {
                $wallet->checkLayout()->associate($checkLayout);
                $wallet->save();
            }

            return $checkLayout->id;

        });

        return to_route('wallets.check.edit', ['wallet' => $wallet_id, 'layout' => $checkLayoutId])
            ->with('success', 'Check layout created successfully.');
    }

    public function edit(Request $request, ChurchWallet $wallet): Response
    {

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

    public function update(UpdateCheckLayoutRequest $request, CheckLayout $checkLayout): RedirectResponse
    {
        $validated = $request->validated();

        $checkLayout->update($validated);

        return back()->with(FlashMessageKey::SUCCESS->value, __('flash.message.updated', [
            'model' => __('Check layout'),
        ]));
    }
}
