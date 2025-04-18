<?php

declare(strict_types=1);

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MissionaryController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Middleware\SetLocale;
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

    Route::middleware(SetLocale::class)
        ->group(function (): void {

            Route::get('/', fn (): string => app()->getLocale())->name('home');

            Route::middleware('auth')->group(function (): void {
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

                Route::put('wallets/{wallet:uuid}/restore', [WalletController::class, 'restore'])
                    ->withTrashed()
                    ->name('wallets.restore');
                Route::resource('wallets', WalletController::class)
                    ->withTrashed()
                    ->scoped([
                        'wallet' => 'uuid',
                    ])
                    ->except(['create', 'edit']);
            });

            require __DIR__.'/settings.php';
            require __DIR__.'/auth.php';

        });

});
