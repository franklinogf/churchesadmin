<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\StoreWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Church;
use App\Models\Member;
use App\Models\Wallet;
use Inertia\Inertia;

final class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $wallets = Wallet::withTrashed()->whereMorphedTo('holder', tenant())->oldest()->get();

        return Inertia::render('wallets/index', [
            'wallets' => WalletResource::collection($wallets),
        ]);
    }

    public function show(Wallet $wallet)
    {
        $wallet->load(['walletTransactions']);

        // $member = Member::latest()->first();
        // $wallet->withdraw(36_45);
        // $wallet->refresh();

        return Inertia::render('wallets/show', [
            'wallet' => new WalletResource($wallet),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request)
    {
        Church::current()->createWallet($request->validated());

        return redirect()->route('wallets.index')->with('success', __('Wallet created successfully.'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        $wallet->update($request->validated());

        return redirect()->route('wallets.index')->with('success', __('Wallet updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return redirect()->route('wallets.index')->with('success', __('Wallet deleted successfully.'));
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Wallet $wallet)
    {
        $wallet->restore();

        return redirect()->route('wallets.index')->with('success', __('Wallet restored successfully.'));
    }
}
