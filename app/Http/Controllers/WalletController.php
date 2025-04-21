<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dtos\WalletMetaDto;
use App\Enums\FlashMessageKey;
use App\Http\Requests\Wallet\StoreWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Church;
use App\Models\Wallet;
use Inertia\Inertia;

final class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $wallets = Church::current()?->wallets()->oldest()->get();

        return Inertia::render('wallets/index', [
            'wallets' => WalletResource::collection($wallets),
        ]);
    }

    public function show(Wallet $wallet)
    {
        $wallet->load(['walletTransactions']);

        return Inertia::render('wallets/show', [
            'wallet' => new WalletResource($wallet),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request)
    {
        $validated = $request->validated();

        $wallet = Church::current()->createWallet(
            [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'meta' => new WalletMetaDto(
                    bank_name: $validated['bank_name'],
                    bank_routing_number: $validated['bank_routing_number'],
                    bank_account_number: $validated['bank_account_number'],
                )->toArray(),
            ]
        );
        $wallet->depositFloat($validated['balance']);

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Wallet')])
        );

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        $validated = $request->validated();
        $wallet->update(
            [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'meta' => new WalletMetaDto(
                    bank_name: $validated['bank_name'],
                    bank_routing_number: $validated['bank_routing_number'],
                    bank_account_number: $validated['bank_account_number'],
                )->toArray(),
            ]
        );

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Wallet')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Wallet')])
        );
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Wallet $wallet)
    {
        $wallet->restore();

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.restored', ['model' => __('Wallet')])
        );
    }
}
