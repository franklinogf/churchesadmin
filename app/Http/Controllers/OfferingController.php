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
use Bavix\Wallet\Services\FormatterServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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
            'offerings' => is_null($date) ? $offerings : OfferingResource::collection($offerings),
            'date' => $date,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {

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

        /**
         * @var array{
         * date:string,donor_id:string|null,
         * offerings: array{
         * wallet_id: string,
         * amount: string,
         * payment_method: string,
         * offering_type: array{id:string, model:string},
         * note: string}[]
         * } $validated
         */
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $action): void {
                foreach ($validated['offerings'] as $offering) {
                    $action->handle([
                        ...$offering,
                        'date' => $validated['date'],
                        'donor_id' => $validated['donor_id'],
                    ]);
                }
            });
        } catch (WalletException $e) {
            return back()
                ->with(FlashMessageKey::ERROR->value, $e->getMessage());

        }

        return to_route('offerings.index', ['date' => $validated['date']])->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('offerings')])
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Offering $offering): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offering $offering): Response
    {

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
         * @var array{date:string,payer_id:int|string,wallet_id:string,amount:string,payment_method:string,offering_type:array{id:string,model:string},note:string} $validated
         */
        $validated = $request->validated();

        try {
            $action->handle($offering, $validated);
        } catch (WalletException $e) {
            return back()
                ->with(FlashMessageKey::ERROR->value, $e->getMessage())
                ->withErrors([
                    'wallet_id' => $e->getMessage(),
                ]);
        }

        return to_route('offerings.index', ['date' => $validated['date']])->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('offerings')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offering $offering, DeleteOfferingAction $action): RedirectResponse
    {
        try {
            $action->handle($offering);
        } catch (WalletException $e) {
            return back()
                ->with(FlashMessageKey::ERROR->value, $e->getMessage());
        }

        return to_route('offerings.index', ['date' => $offering->date])->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('offerings')])
        );
    }

    private function formatAmount(float|int|string $amount): string
    {
        return app(FormatterServiceInterface::class)->floatValue($amount, 2);
    }
}
