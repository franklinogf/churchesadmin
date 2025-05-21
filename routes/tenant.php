<?php

declare(strict_types=1);

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\CheckLayoutController;
use App\Http\Controllers\ConfirmMultipleCheckController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\GenerateCheckNumberController;
use App\Http\Controllers\LoginLinkController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MissionaryController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\OfferingTypeController;
use App\Http\Controllers\Pdf\CheckPdfController;
use App\Http\Controllers\Pdf\ChecksPdfController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletCheckLayoutController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    Middleware\InitializeTenancyBySubdomain::class,
    Middleware\PreventAccessFromUnwantedDomains::class,
    Middleware\ScopeSessions::class,
])->group(function (): void {

    Route::get('/locale/{locale}', function (string $locale) {
        session(['locale' => $locale]);

        return redirect()->back();
    })->name('locale');
    Route::post('loginLink', LoginLinkController::class)->name('loginLink');

    Route::redirect('/', 'dashboard')->name('home');

    Route::middleware('auth:tenant')->group(function (): void {
        Route::get('dashboard', fn () => inertia('dashboard'))->name('dashboard');

        Route::resource('users', UserController::class)
            ->except(['show']);

        Route::put('members/{member}/restore', [MemberController::class, 'restore'])
            ->withTrashed()
            ->name('members.restore');
        Route::delete('members/{member}/forceDelete', [MemberController::class, 'forceDelete'])
            ->withTrashed()
            ->name('members.forceDelete');
        Route::resource('members', MemberController::class);

        Route::put('missionaries/{missionary}/restore', [MissionaryController::class, 'restore'])
            ->withTrashed()
            ->name('missionaries.restore');
        Route::delete('missionaries/{missionary}/forceDelete', [MissionaryController::class, 'forceDelete'])
            ->withTrashed()
            ->name('missionaries.forceDelete');
        Route::resource('missionaries', MissionaryController::class);

        Route::resource('skills', SkillController::class)
            ->parameter('skills', 'tag')
            ->except(['show', 'create', 'edit']);

        Route::resource('categories', CategoryController::class)
            ->parameter('categories', 'tag')
            ->except(['show', 'create', 'edit']);

        // Accounting
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

        // codes
        Route::prefix('codes')->name('codes.')->group(function (): void {
            Route::resource('offeringTypes', OfferingTypeController::class)
                ->except(['show', 'create', 'edit']);
            Route::resource('expenseTypes', ExpenseTypeController::class)
                ->except(['show', 'create', 'edit']);
        });

        require __DIR__.'/tenant/settings.php';
        // require __DIR__.'/tenant/messages.php';
    });

    require __DIR__.'/tenant/auth.php';

});
