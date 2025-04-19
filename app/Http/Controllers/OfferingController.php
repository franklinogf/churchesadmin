<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dtos\DepositMetaDto;
use App\Enums\FlashMessageKey;
use App\Enums\OfferingType;
use App\Http\Requests\Offering\StoreOfferingRequest;
use App\Http\Requests\Offering\UpdateOfferingRequest;
use App\Http\Resources\Offering\OfferingResource;
use App\Models\Church;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\Wallet;
use Inertia\Inertia;
use Inertia\Response;

final class OfferingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $offerings = Church::current()->transactions()->with('wallet')->get(); // ->whereNotNull('meta->payer_id')->get();

        return Inertia::render('offerings/index', [
            'offerings' => OfferingResource::collection($offerings),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {

        $offeringTypes = OfferingType::options();
        $wallets = Church::current()->wallets()->get()->map(fn ($wallet) => [
            'value' => $wallet->id,
            'label' => $wallet->name,
        ])->toArray();
        $members = Member::all()->map(fn ($member) => [
            'value' => $member->id,
            'label' => "{$member->name} {$member->last_name}",
        ])->toArray();

        return Inertia::render('offerings/create', [
            'offeringTypes' => $offeringTypes,
            'wallets' => $wallets,
            'members' => $members,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferingRequest $request)
    {

        $validated = $request->validated();

        collect($validated['offerings'])->each(function ($offering) use ($validated) {
            $wallet = Wallet::find($offering['wallet_id']);

            $wallet->depositFloat(
                $offering['amount'],
                new DepositMetaDto(
                    payer_id: $validated['payer_id'],
                    date: $validated['date'],
                    offering_type: $validated['offering_type'],
                    message: $validated['message'],
                )->toArray(),
            );
        });

        return to_route('offerings.index')->with(FlashMessageKey::SUCCESS->value, __('Offering created successfully'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferingRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
