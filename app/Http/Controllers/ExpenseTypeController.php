<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageKey;
use App\Http\Requests\Code\StoreExpenseTypeRequest;
use App\Http\Requests\Code\UpdateExpenseTypeRequest;
use App\Http\Resources\Codes\ExpenseTypeResource;
use App\Models\ExpenseType;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $expenseTypes = ExpenseType::latest()->get();

        return Inertia::render('codes/expenseTypes/index', [
            'expenseTypes' => ExpenseTypeResource::collection($expenseTypes),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseTypeRequest $request): RedirectResponse
    {
        ExpenseType::create($request->validated());

        return to_route('codes.expenseTypes.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.created', ['model' => 'Expense type']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseTypeRequest $request, ExpenseType $expenseType): RedirectResponse
    {
        $expenseType->update($request->validated());

        return to_route('codes.expenseTypes.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.updated', ['model' => 'Expense type']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseType $expenseType): RedirectResponse
    {
        $expenseType->delete();

        return to_route('codes.expenseTypes.index')->with(FlashMessageKey::SUCCESS->value,
            __('flash.message.deleted', ['model' => 'Expense type']));
    }
}
