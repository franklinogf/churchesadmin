<?php

declare(strict_types=1);

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TenantGeneralController;
use App\Http\Controllers\Settings\TenantLanguageController;
use App\Http\Controllers\Settings\TenantLogoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth:tenant')->group(function (): void {
    Route::redirect('settings', 'settings/profile');

    Route::prefix('settings')->group(function (): void {
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::get('appearance', fn () => Inertia::render('settings/appearance'))->name('appearance');

        Route::get('church/language', [TenantLanguageController::class, 'edit'])->name('church.language.edit');
        Route::patch('church/language', [TenantLanguageController::class, 'update'])->name('church.language.update');

        Route::get('church/general', [TenantGeneralController::class, 'edit'])->name('church.general.edit');
        Route::put('church/general', [TenantGeneralController::class, 'update'])->name('church.general.update');

        Route::post('church/logo', TenantLogoController::class)->name('church.logo');
    });
});
