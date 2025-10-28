<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Wallet\CreateWalletAction;
use App\Actions\Wallet\DeleteWalletAction;
use App\Actions\Wallet\RestoreWalletAction;
use App\Actions\Wallet\UpdateWalletAction;
use App\Enums\FlashMessageKey;
use App\Enums\TransactionMetaType;
use App\Enums\TransactionType;
use App\Exceptions\WalletException;
use App\Http\Requests\Wallet\StoreWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Http\Resources\Wallet\ChurchWalletResource;
use App\Models\ChurchWallet;
use App\Models\CurrentYear;
use App\Models\TenantUser;
use Bavix\Wallet\Services\FormatterService;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', ChurchWallet::class);

        $wallets = ChurchWallet::query()
            ->oldest()
            ->with('checkLayout')
            ->withCount([
                'transactions' => function (Builder $query): void {
                    $query->whereNot('meta->type', TransactionMetaType::INITIAL->value);
                },
            ])
            ->get();

        return Inertia::render('wallets/index', [
            'wallets' => ChurchWalletResource::collection($wallets),
        ]);
    }

    public function show(ChurchWallet $wallet, #[CurrentUser] TenantUser $user): Response
    {
        Gate::authorize('view', $wallet);

        $wallet->load([
            'transactions.wallet' => function (BelongsTo $belongsTo): void {
                /** @phpstan-ignore-next-line */
                $belongsTo->withTrashed();
            },
        ]);

        $currentYear = $user->currentYear;
        $prevYear = CurrentYear::where('year', $currentYear->year - 1)->first();

        $initialRow = null;

        if ($prevYear !== null) {
            $previousTransactions = $wallet->transactions()->withoutGlobalScopes()
                ->where('meta->year', $prevYear->id)
                ->get();

            $previousBalance = 0;

            foreach ($previousTransactions as $txn) {
                $previousBalance += (int) $txn->amount;
            }

            $initialRow = [
                'id' => 0,
                'uuid' => 0,
                'amount' => $previousBalance,
                'amountFloat' => app(FormatterService::class)->floatValue($previousBalance, 2),
                'confirmed' => true,
                'type' => TransactionType::PREVIOUS->value,
                'meta' => null,
                'createdAt' => $prevYear->start_date->format('Y-m-d'),
            ];
        }

        return Inertia::render('wallets/show', [
            'wallet' => new ChurchWalletResource($wallet),
            'initialRow' => $prevYear !== null ? $initialRow : null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request, CreateWalletAction $action): RedirectResponse
    {
        /**
         * @var array{
         * balance:string|null,
         * name:string,
         * description:string|null,
         * bank_name:string,
         * bank_routing_number:string,
         * bank_account_number:string} $validated
         */
        $validated = $request->validated();
        try {
            $action->handle($validated);
        } catch (WalletException $e) {
            return back()->with(
                FlashMessageKey::ERROR->value,
                $e->getMessage()
            );
        }

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Wallet')])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, ChurchWallet $wallet, UpdateWalletAction $action): RedirectResponse
    {
        /**
         * @var array{
         * balance?:string|null,
         * name:string,
         * description:string|null,
         * bank_name:string,
         * bank_routing_number:string,
         * bank_account_number:string} $validated
         */
        $validated = $request->validated();

        $action->handle($wallet, $validated);

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Wallet')])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChurchWallet $wallet, DeleteWalletAction $action): RedirectResponse
    {
        Gate::authorize('delete', $wallet);

        $action->handle($wallet);

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', replace: ['model' => __('Wallet')])
        );
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(ChurchWallet $wallet, RestoreWalletAction $action): RedirectResponse
    {
        Gate::authorize('restore', $wallet);
        $action->handle($wallet);

        return redirect()->route('wallets.index')->with(
            FlashMessageKey::SUCCESS->value,
            __('flash.message.restored', ['model' => __('Wallet')])
        );
    }
}
