<?php

declare(strict_types=1);

use App\Http\Controllers\DeactivationCodeController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\OfferingTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('codes')->name('codes.')->group(function (): void {
    Route::resource('offeringTypes', OfferingTypeController::class)
        ->except(['show', 'create', 'edit']);
    Route::resource('expenseTypes', ExpenseTypeController::class)
        ->except(['show', 'create', 'edit']);
    Route::resource('deactivationCodes', DeactivationCodeController::class)
        ->except(['show', 'create', 'edit']);
});
