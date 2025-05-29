<?php

declare(strict_types=1);

use App\Http\Controllers\LoginLinkController;
use App\Http\Controllers\SessionController;
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

    Route::post('loginLink', LoginLinkController::class)->name('loginLink');

    Route::redirect('/', 'dashboard')->name('home');

    Route::middleware('auth:tenant')->group(function (): void {
        // This route is used to set session variables for the application
        Route::post('session', SessionController::class)->name('session');

        require __DIR__.'/tenant/main.php';
        require __DIR__.'/tenant/codes.php';
        require __DIR__.'/tenant/accounting.php';
        require __DIR__.'/tenant/settings.php';
        require __DIR__.'/tenant/communication.php';
    });

    require __DIR__.'/tenant/auth.php';

});
