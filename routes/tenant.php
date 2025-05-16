<?php

declare(strict_types=1);

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangeWalletLayoutController;
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
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Barryvdh\DomPDF\Facade\Pdf;
use Bavix\Wallet\Services\FormatterService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use NumberToWords\NumberToWords;
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
        Route::put('wallets/{wallet}/check-layout', ChangeWalletLayoutController::class)->name('wallets.check.change-layout');
        Route::get('wallets/{wallet}/check-layout', [CheckLayoutController::class, 'edit'])->name('wallets.check.edit');
        Route::post('wallets/{wallet}/check-layout', [CheckLayoutController::class, 'store'])->name('wallets.check.store');
        Route::put('wallets/{wallet}/check-layout/{checkLayout}', [CheckLayoutController::class, 'update'])->name('wallets.check.update');

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

        Route::resource('checks', CheckController::class);

        // codes
        Route::prefix('codes')->name('codes.')->group(function (): void {
            Route::resource('offeringTypes', OfferingTypeController::class)
                ->except(['show', 'create', 'edit']);
            Route::resource('expenseTypes', ExpenseTypeController::class)
                ->except(['show', 'create', 'edit']);
        });
    });

    Route::get('pdf', function () {
        $dimensions = [
            'width' => 580,
            'height' => 748,
        ];

        $fieldsLayout = [
            'payee' => [
                'position' => [
                    'x' => 84.00003051757812,
                    'y' => 96,
                ],
            ],
            'amount' => [
                'position' => [
                    'x' => 462.3999938964844,
                    'y' => 94.39999389648438,
                ],
            ],
            'amountWords' => [
                'position' => [
                    'x' => 28,
                    'y' => 117.60000610351562,
                ],
            ],
            'date' => [
                'position' => [
                    'x' => 476.0000305175781,
                    'y' => 60.79998779296875,
                ],
            ],
            'memo' => [
                'position' => [
                    'x' => 56,
                    'y' => 174.40005493164062,
                ],
            ],
            'signature' => [
                'position' => [
                    'x' => 372,
                    'y' => 171.19998168945312,
                ],
            ],
        ];

        $fields = [
            'date' => '2023-10-01',
            'amount' => '589.45',
            'amountWords' => NumberToWords::transformCurrency('en', (int) app(FormatterService::class)->intValue('589.45', 2), 'USD'),
            'payee' => 'Name of Payee',
            'memo' => 'Memo',
            'signature' => 'Signature',
        ];

        // dd(Storage::path('check.webp'));
        $pdf = Pdf::loadView('pdf.check', [
            'checkImage' => Storage::path('check.webp'),
            'fields' => $fields,
            'fieldsLayout' => $fieldsLayout,
        ])->setPaper([0, 0, $dimensions['width'] * 0.75, $dimensions['height'] * 0.75]);

        return $pdf->stream();
    });

    require __DIR__.'/settings.php';
    require __DIR__.'/auth.php';

});
