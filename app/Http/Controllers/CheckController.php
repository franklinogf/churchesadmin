<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Check\CreateCheckAction;
use App\Actions\Check\DeleteCheckAction;
use App\Actions\Check\UpdateCheckAction;
use App\Enums\CheckType;
use App\Enums\FlashMessageKey;
use App\Helpers\SelectOption;
use App\Http\Requests\Check\StoreCheckRequest;
use App\Http\Requests\Check\UpdateCheckRequest;
use App\Http\Resources\Check\CheckResource;
use App\Models\Check;
use App\Models\Church;
use App\Models\Member;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $checks = Check::latest()->get();

        return Inertia::render('checks/index', [
            'checks' => CheckResource::collection($checks),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $wallets = SelectOption::create(Church::current()?->wallets()->get(), value: 'slug');
        $members = SelectOption::create(Member::all(), labels: ['name', 'last_name']);
        $checkTypes = CheckType::options();

        return Inertia::render('checks/create', [
            'wallets' => $wallets,
            'members' => $members,
            'checkTypes' => $checkTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCheckRequest $request, CreateCheckAction $action): RedirectResponse
    {
        $validated = $request->validated();

        $wallet = Church::current()->getWallet($validated['wallet_id']);

        if (! $wallet) {
            return back()->with(FlashMessageKey::ERROR->value, __('flash.message.wallet_not_found'));
        }

        try {
            $action->handle($validated, $wallet);

        } catch (InsufficientFunds) {

            return back()->with(FlashMessageKey::ERROR->value,
                __('flash.message.insufficient_funds', ['wallet' => $wallet->name])
            );
        }

        return to_route('checks.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => __('Check')])
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(Check $check)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Check $check): Response
    {
        $wallets = SelectOption::create(Church::current()?->wallets()->get(), value: 'slug');
        $members = SelectOption::create(Member::all(), labels: ['name', 'last_name']);
        $checkTypes = CheckType::options();

        return Inertia::render('checks/edit', [
            'wallets' => $wallets,
            'members' => $members,
            'checkTypes' => $checkTypes,
            'check' => new CheckResource($check),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCheckRequest $request, Check $check, UpdateCheckAction $action): RedirectResponse
    {
        $validated = $request->validated();

        $wallet = Church::current()->getWallet($validated['wallet_id']);

        if (! $wallet) {
            return back()->with(FlashMessageKey::ERROR->value, __('flash.message.wallet_not_found'));
        }

        try {

            $action->handle($check, $validated, $wallet);

        } catch (InsufficientFunds) {

            return back()->with(FlashMessageKey::ERROR->value,
                __('flash.message.insufficient_funds', ['wallet' => $wallet->name])
            );
        }

        return to_route('checks.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => __('Check')])
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Check $check, DeleteCheckAction $action): RedirectResponse
    {
        $action->handle($check);

        return to_route('checks.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => __('Check')])
        );
    }
}
