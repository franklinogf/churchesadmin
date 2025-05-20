<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\Check\StoreCheckLayoutRequest;
use App\Http\Requests\Check\UpdateCheckLayoutRequest;
use App\Models\CheckLayout;
use App\Models\ChurchWallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

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
                'fields' => null,
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
            ->with(FlashMessageKey::SUCCESS->value, __('flash.message.created', [
                'model' => __('Check layout'),
            ]));
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
