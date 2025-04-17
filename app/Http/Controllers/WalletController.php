<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\StoreWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Wallet;
use Inertia\Inertia;

final class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $wallets = Wallet::whereMorphedTo('holder', tenant())->oldest()->get();

        return Inertia::render('wallets/index', [
            'wallets' => WalletResource::collection($wallets),
        ]);
    }

    public function show(Wallet $wallet)
    {
        dd($wallet);
        // return Inertia::render('wallets/show', [
        //     'wallet' => new WalletResource($wallet),
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
