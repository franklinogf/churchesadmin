<?php

declare(strict_types=1);

use App\Http\Controllers\CheckController;
use App\Http\Controllers\CheckLayoutController;
use App\Http\Controllers\ConfirmMultipleCheckController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GenerateCheckNumberController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\Pdf\CheckPdfController;
use App\Http\Controllers\Pdf\ChecksPdfController;
use App\Http\Controllers\WalletCheckLayoutController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('check-layout', [CheckLayoutController::class, 'store'])->name('check-layout.store');
Route::put('check-layout/{checkLayout}', [CheckLayoutController::class, 'update'])->name('check-layout.update');

Route::put('wallets/{wallet}/check-layout', [WalletCheckLayoutController::class, 'update'])->name('wallets.check.update');
Route::get('wallets/{wallet}/check-layout', [WalletCheckLayoutController::class, 'edit'])->name('wallets.check.edit');
Route::put('wallets/{wallet}/restore', [WalletController::class, 'restore'])
    ->withTrashed()
    ->name('wallets.restore');

Route::resource('wallets', WalletController::class)
    ->withTrashed()
    ->except(['create', 'edit']);

Route::resource('offerings', OfferingController::class);

Route::resource('expenses', ExpenseController::class);

Route::patch('checks/generate-check-number', GenerateCheckNumberController::class)
    ->name('checks.generate-check-number');
Route::patch('checks/confirm', ConfirmMultipleCheckController::class)
    ->name('checks.confirm-multiple');

Route::get('checks/pdf', ChecksPdfController::class)->name('checks.pdf.multiple');
Route::get('checks/{check}/pdf', CheckPdfController::class)->name('checks.pdf');

Route::resource('checks', CheckController::class);
