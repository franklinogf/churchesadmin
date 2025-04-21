<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Enums\PaymentMethod;
use App\Http\Requests\Offering\StoreOfferingRequest;
use App\Http\Requests\Offering\UpdateOfferingRequest;
use App\Http\Resources\Offering\OfferingResource;
use App\Models\Church;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\Offering;
use App\Models\OfferingType;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class OfferingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $offerings = Offering::latest('date')->get();

        return Inertia::render('offerings/index', [
            'offerings' => OfferingResource::collection($offerings),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {

        $paymentMethods = PaymentMethod::options();
        $wallets = Church::current()->wallets()->get()->map(fn ($wallet) => [
            'value' => $wallet->id,
            'label' => $wallet->name,
        ])->toArray();
        $members = Member::all()->map(fn ($member) => [
            'value' => $member->id,
            'label' => "{$member->name} {$member->last_name}",
        ])->toArray();

        $missionaries = Missionary::all()->map(fn ($missionary) => [
            'value' => $missionary->id,
            'label' => "{$missionary->name} {$missionary->last_name}",
        ])->toArray();

        $offeringTypes = OfferingType::all()->map(fn ($type) => [
            'value' => $type->id,
            'label' => $type->name,
        ])->toArray();

        return Inertia::render('offerings/create', [
            'paymentMethods' => $paymentMethods,
            'wallets' => $wallets,
            'members' => $members,
            'offeringTypes' => $offeringTypes,
            'missionaries' => $missionaries,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferingRequest $request)
    {

        $validated = $request->validated();
        DB::transaction(function () use ($validated) {
            collect($validated['offerings'])->each(function ($offering) use ($validated) {
                $wallet = Wallet::find($offering['wallet_id']);

                $transaction = $wallet->depositFloat(
                    $offering['amount']
                );

                Offering::create([
                    'transaction_id' => $transaction->id,
                    'donor_id' => $validated['payer_id'] === 'non_member' ? null : $validated['payer_id'],
                    'recipient_id' => $offering['recipient_id'],
                    'date' => Carbon::parse($validated['date'])->setTimeFrom(now()),
                    'payment_method' => $offering['payment_method'],
                    'offering_type_id' => $offering['offering_type_id'],
                    'note' => $offering['note'],
                ]);
            });
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
