<?php

declare(strict_types=1);

use App\Http\Controllers\MembersController;
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
    Route::get('/', fn () => to_route('home', app()->getLocale()))->name('index');

    Route::prefix('{locale}')
        ->where(['locale' => '[a-zA-Z]{2}'])
        ->middleware([SetLocale::class])
        ->group(function (): void {
            Route::get('/', fn (): string => 'Hello, world!')->name('home');
            Route::middleware(['auth'])->group(function (): void {
                Route::get('dashboard', fn () => inertia('dashboard'))->name('dashboard');
            });

            require __DIR__.'/settings.php';
            require __DIR__.'/auth.php';

            Route::resource('members', MembersController::class)->names('members');
        });

});
