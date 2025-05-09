<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dtos\WalletMetaDto;
use App\Enums\FlashMessageKey;
use App\Enums\TransactionMetaType;
use App\Http\Requests\Wallet\StoreWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Http\Resources\Wallet\WalletResource;
use App\Models\Church;
use App\Models\ChurchWallet;
use Bavix\Wallet\Services\FormatterService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {

        $wallets = Church::current()?->wallets()
            ->withCount([
                'walletTransactions' => function (Builder $query): void {
                    $query->whereNot('meta->type', TransactionMetaType::INITIAL->value);
                }
            ])
            ->withTrashed()
            ->oldest()
            ->get();

        return Inertia::render('wallets/index', [
            'wallets' => WalletResource::collection($wallets),
        ]);
    }

    public function show(ChurchWallet $wallet): Response
    {
        $wallet->load([
            'walletTransactions.wallet' => function (BelongsTo $belongsTo): void {
                /** @phpstan-ignore-next-line */
                $belongsTo->withTrashed();
            }
        ]);

        return Inertia::render('wallets/show', [
            'wallet' => new WalletResource($wallet),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request): RedirectResponse
    {
        /**
         * @var array{balance:string|null,name:string,description:string,bank_name:string,bank_routing_number:string,bank_account_number:string} $validated
         */
        $validated = $request->validated();

        $wallet = Church::current()?->createWallet(
            [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'meta' => new WalletMetaDto(
                    $validated['bank_name'],
                    $validated['bank_routing_number'],
                    $validated['bank_account_number'],
                )->toArray(),
            ]
        );
        if ($validated['balance'] !== null) {
            $wallet?->depositFloat($validated['balance'], ['type' => TransactionMetaType::INITIAL->value]);
        }

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Wallet')])
        );

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, ChurchWallet $wallet, FormatterService $formatterService): RedirectResponse
    {
        /**
         * @var array{balance:string|null,name:string,description:string,bank_name:string,bank_routing_number:string,bank_account_number:string} $validated
         */
        $validated = $request->validated();
        $wallet->update(
            [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'meta' => new WalletMetaDto(
                    $validated['bank_name'],
                    $validated['bank_routing_number'],
                    $validated['bank_account_number'],
                )->toArray(),
            ]
        );
        if ($validated['balance'] !== null) {

            $transaction = $wallet->transactions()->where('meta->type', TransactionMetaType::INITIAL->value)->first();

            if ($transaction) {
                $transaction->update(['amount' => $formatterService->intValue($validated['balance'], 2)]);
                $wallet->refreshBalance();
            } else {
                $wallet->depositFloat($validated['balance'], ['type' => TransactionMetaType::INITIAL->value]);
            }

        }

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Wallet')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChurchWallet $wallet): RedirectResponse
    {
        $wallet->delete();

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', replace: ['model' => __('Wallet')])
        );
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(ChurchWallet $wallet): RedirectResponse
    {
        $wallet->restore();

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.restored', ['model' => __('Wallet')])
        );
    }
}
