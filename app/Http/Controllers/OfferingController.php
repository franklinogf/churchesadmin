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
use Bavix\Wallet\Services\FormatterServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class OfferingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(?string $date = null): Response
    {

        $offerings = Offering::query()
            ->when($date !== null, fn ($query) => $query->whereDate('date', $date))
            ->get()
            ->when($date === null, fn ($collection) => $collection->groupBy(fn ($offering) => $offering->date->format('Y-m-d'))
                ->map(function ($group) {

                    /** @var string $sum */
                    $sum = $group->sum('transaction.amount');
                    $data = [
                        'date' => $group->first()?->date->format('Y-m-d'),
                        'total' => $this->formatAmount($sum),
                    ];

                    foreach (PaymentMethod::cases() as $paymentMethod) {
                        /** @var string $groupSum */
                        $groupSum = $group->where('payment_method', $paymentMethod->value)->sum('transaction.amount');
                        $data[$paymentMethod->value] = $this->formatAmount($groupSum);
                    }

                    return $data;
                })->values()->toArray());

        return Inertia::render('offerings/index', [
            'offerings' => $date !== null ? OfferingResource::collection($offerings) : $offerings,
            'date' => $date,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {

        $paymentMethods = PaymentMethod::options();
        $wallets = Church::current()?->wallets()->get()->map(fn ($wallet): array => [
            'value' => $wallet->id,
            'label' => $wallet->name,
        ])->toArray();
        $members = Member::all()->map(fn ($member): array => [
            'value' => $member->id,
            'label' => "{$member->name} {$member->last_name}",
        ])->toArray();

        $missionaries = [
            'heading' => __('Missionaries'),
            'model' => Relation::getMorphAlias(Missionary::class),
            'options' => Missionary::all()->map(fn ($missionary): array => [
                'value' => $missionary->id,
                'label' => "{$missionary->name} {$missionary->last_name}",
            ])->toArray(),
        ];

        $offeringTypes = [
            'heading' => __('Offering types'),
            'model' => Relation::getMorphAlias(OfferingType::class),
            'options' => OfferingType::all()->map(fn ($offeringType): array => [
                'value' => $offeringType->id,
                'label' => $offeringType->name,
            ])->toArray(),
        ];

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
    public function store(StoreOfferingRequest $request): RedirectResponse
    {
        /**
         * @var array{
         * date:string,payer_id:int|string,
         * offerings: array{
         * wallet_id: int,
         * amount: float,
         * payment_method: string,
         * offering_type: array{id:string, model:string},
         * note: string}[]
         * } $validated
         */
        $validated = $request->validated();

        DB::transaction(function () use ($validated): void {
            collect($validated['offerings'])->each(function (array $offering) use ($validated): void {
                $wallet = Wallet::find($offering['wallet_id']);

                $transaction = $wallet?->depositFloat(
                    $offering['amount']
                );

                Offering::create([
                    'transaction_id' => $transaction?->id,
                    'donor_id' => $validated['payer_id'] === 'non_member' ? null : $validated['payer_id'],
                    'date' => Carbon::parse($validated['date'])->setTimeFrom(now()),
                    'payment_method' => $offering['payment_method'],
                    'offering_type_id' => $offering['offering_type']['id'],
                    'offering_type_type' => $offering['offering_type']['model'],
                    'note' => $offering['note'],
                ]);
            });
        });

        return to_route('offerings.index', ['date' => $validated['date']])->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('offerings')])
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offering $offering): Response
    {
        $paymentMethods = PaymentMethod::options();
        $wallets = Church::current()?->wallets()->get()->map(fn ($wallet): array => [
            'value' => $wallet->id,
            'label' => $wallet->name,
        ])->toArray();
        $members = Member::all()->map(fn ($member): array => [
            'value' => $member->id,
            'label' => "{$member->name} {$member->last_name}",
        ])->toArray();

        $missionaries = [
            'heading' => __('Missionaries'),
            'model' => Relation::getMorphAlias(Missionary::class),
            'options' => Missionary::all()->map(fn ($missionary): array => [
                'value' => $missionary->id,
                'label' => "{$missionary->name} {$missionary->last_name}",
            ])->toArray(),
        ];

        $offeringTypes = [
            'heading' => __('Offering types'),
            'model' => Relation::getMorphAlias(OfferingType::class),
            'options' => OfferingType::all()->map(fn ($offeringType): array => [
                'value' => $offeringType->id,
                'label' => $offeringType->name,
            ])->toArray(),
        ];

        return Inertia::render('offerings/edit', [
            'paymentMethods' => $paymentMethods,
            'wallets' => $wallets,
            'members' => $members,
            'offeringTypes' => $offeringTypes,
            'missionaries' => $missionaries,
            'offering' => new OfferingResource($offering),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferingRequest $request, Offering $offering): RedirectResponse
    {
        /**
         * @var array{date:string,payer_id:int|string,wallet_id:int,amount:float,payment_method:string,offering_type:array{id:string,model:string},note:string} $validated
         */
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $offering): void {

            $offering->update([
                'donor_id' => $validated['payer_id'] === 'non_member' ? null : $validated['payer_id'],
                'date' => Carbon::parse($validated['date'])->setTimeFrom(now()),
                'payment_method' => $validated['payment_method'],
                'offering_type_id' => $validated['offering_type']['id'],
                'offering_type_type' => $validated['offering_type']['model'],
                'note' => $validated['note'],
            ]);

            if ($validated['wallet_id'] !== $offering->transaction->wallet_id) {
                $wallet = Wallet::find($validated['wallet_id']);

                $transaction = $wallet?->depositFloat(
                    $validated['amount']
                );

                $offering->transaction->forceDelete();

                // Update the transaction ID if the wallet has changed
                $offering->update([
                    'transaction_id' => $transaction?->id,
                ]);
                $wallet?->refreshBalance();
            } else {
                $offering->transaction->update([
                    'amount' => $validated['amount'],
                ]);
                $offering->transaction->wallet->refreshBalance();
            }

        });

        return to_route('offerings.index', ['date' => $validated['date']])->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('offerings')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offering $offering): RedirectResponse
    {
        $wallet = $offering->transaction->wallet;
        $date = $offering->date->format('Y-m-d');
        $offering->transaction->forceDelete();
        $offering->delete();
        $wallet->refreshBalance();

        return to_route('offerings.index', ['date' => $date])->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('offerings')])
        );
    }

    private function formatAmount(float|int|string $amount): string
    {
        return app(FormatterServiceInterface::class)->floatValue($amount, 2);
    }
}
