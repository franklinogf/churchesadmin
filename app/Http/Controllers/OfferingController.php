<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Offering\CreateOfferingAction;
use App\Actions\Offering\DeleteOfferingAction;
use App\Actions\Offering\UpdateOfferingAction;
use App\Enums\FlashMessageKey;
use App\Enums\PaymentMethod;
use App\Exceptions\WalletException;
use App\Http\Requests\Offering\ListOfferingRequest;
use App\Http\Requests\Offering\StoreOfferingRequest;
use App\Http\Requests\Offering\UpdateOfferingRequest;
use App\Http\Resources\Offering\OfferingResource;
use App\Models\ChurchWallet;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\Offering;
use App\Models\OfferingType;
use App\Support\SelectOption;
use Bavix\Wallet\Services\FormatterService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class OfferingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListOfferingRequest $request): Response
    {
        $date = $request->string('date')->toString() ?: null;

        $offerings = Offering::query()
            ->when(! is_null($date), fn (Builder $query) => $query->whereDate('date', $date))
            ->get()
            ->when(is_null($date), fn (Collection $collection) => $collection->groupBy(fn (Offering $offering): string => $offering->date->format('Y-m-d'))
                ->map(function (Collection $group): array {

                    /** @var int $sum */
                    $sum = $group->sum('transaction.amount');

                    $data = [
                        'date' => $group->first()?->date->format('Y-m-d'),
                        'total' => $this->formatAmount($sum),
                    ];

                    foreach (PaymentMethod::cases() as $paymentMethod) {
                        /** @var int $groupSum */
                        $groupSum = $group->where('payment_method', $paymentMethod->value)->sum('transaction.amount');
                        $data[$paymentMethod->value] = $this->formatAmount($groupSum);
                    }

                    return $data;
                })->values()->toArray());

        return Inertia::render('offerings/index', [
            'offerings' => is_null($date) ? $offerings : OfferingResource::collection($offerings),
            'date' => $date,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Offering::class);

        $paymentMethods = PaymentMethod::options();
        $walletsOptions = SelectOption::create(ChurchWallet::all());
        $membersOptions = SelectOption::create(Member::all(), labels: ['name', 'last_name']);

        $missionariesOptions = SelectOption::createForMultiple(
            __('Missionaries'),
            Missionary::all(),
            labels: ['name', 'last_name'],
        );

        $offeringTypesOptions = SelectOption::createForMultiple(
            __('Offering types'),
            OfferingType::all(),
        );

        return Inertia::render('offerings/create', [
            'paymentMethods' => $paymentMethods,
            'walletsOptions' => $walletsOptions,
            'membersOptions' => $membersOptions,
            'offeringTypesOptions' => $offeringTypesOptions,
            'missionariesOptions' => $missionariesOptions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferingRequest $request, CreateOfferingAction $action): RedirectResponse
    {

        try {

            DB::transaction(function () use ($request, $action): void {
                /**
                 * @var array{
                 *  wallet_id:string,
                 *  payment_method:string,
                 *  offering_type:array{id:string,model:string},
                 *  amount:string,
                 *  note:string|null
                 * } $offering
                 */
                foreach ($request->array('offerings') as $offering) {
                    $action->handle([
                        'donor_id' => $request->string('donor_id')->value() ?: null,
                        'date' => $request->string('date')->value(),
                        'wallet_id' => $offering['wallet_id'],
                        'payment_method' => PaymentMethod::from($offering['payment_method']),
                        'offering_type' => $offering['offering_type'],
                        'amount' => $offering['amount'],
                        'note' => $offering['note'],
                    ]);
                }
            });
        } catch (WalletException $e) {
            return back()->with(FlashMessageKey::ERROR->value, $e->getMessage());
        }

        return to_route('offerings.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Offering')])
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Offering $offering): void
    {
        Gate::authorize('view', $offering);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offering $offering): Response
    {
        Gate::authorize('update', $offering);

        $paymentMethods = PaymentMethod::options();
        $walletsOptions = SelectOption::create(ChurchWallet::all());
        $membersOptions = SelectOption::create(Member::all(), labels: ['name', 'last_name']);

        $missionariesOptions = SelectOption::createForMultiple(
            __('Missionaries'),
            Missionary::all(),
            labels: ['name', 'last_name'],
        );

        $offeringTypesOptions = SelectOption::createForMultiple(
            __('Offering types'),
            OfferingType::all(),
        );

        return Inertia::render('offerings/edit', [
            'paymentMethods' => $paymentMethods,
            'walletsOptions' => $walletsOptions,
            'membersOptions' => $membersOptions,
            'offeringTypesOptions' => $offeringTypesOptions,
            'missionariesOptions' => $missionariesOptions,
            'offering' => new OfferingResource($offering),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferingRequest $request, Offering $offering, UpdateOfferingAction $action): RedirectResponse
    {
        /**
         * @var array{
         * donor_id:string|null,
         * date:string,
         *  wallet_id:string,
         *  payment_method:string,
         *  offering_type:array{id:string,model:string},
         *  amount:string,
         *  note:string|null
         * } $validated
         */
        $validated = $request->validated();
        try {
            $action->handle($offering, $validated);
        } catch (WalletException $e) {
            return back()->with(FlashMessageKey::ERROR->value, $e->getMessage());
        }

        return to_route('offerings.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Offering')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offering $offering, DeleteOfferingAction $action): RedirectResponse
    {
        Gate::authorize('delete', $offering);

        try {
            $action->handle($offering);
        } catch (WalletException $e) {
            return back()->with(FlashMessageKey::ERROR->value, $e->getMessage());
        }

        return to_route('offerings.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Offering')])
        );
    }

    private function formatAmount(int $amount): string
    {
        $formatter = new FormatterService;

        return $formatter->floatValue($amount, 2);
    }
}
