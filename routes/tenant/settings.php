<?php

declare(strict_types=1);

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TenantGeneralController;
use App\Http\Controllers\Settings\TenantLanguageController;
use App\Http\Controllers\Settings\TenantYearEndController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('settings')->group(function (): void {
    Route::redirect('/', 'settings/profile')->name('settings');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('appearance', fn () => Inertia::render('settings/appearance'))->name('appearance');

    Route::prefix('church')->group(function (): void {
        Route::redirect('/', 'church/general')->name('church.settings');

        Route::get('language', [TenantLanguageController::class, 'edit'])->name('church.language.edit');
        Route::patch('language', [TenantLanguageController::class, 'update'])->name('church.language.update');

        Route::get('general', [TenantGeneralController::class, 'edit'])->name('church.general.edit');
        Route::post('general', [TenantGeneralController::class, 'update'])->name('church.general.update');

        Route::get('year-end-closing', [TenantYearEndController::class, 'edit'])->name('church.general.year-end.edit');
        Route::post('year-end-closing', [TenantYearEndController::class, 'update'])->name('church.general.year-end.update');

    });
});
