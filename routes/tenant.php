<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImpersonateUserController;
use App\Http\Controllers\LoginLinkController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromUnwantedDomains;
use Stancl\Tenancy\Middleware\ScopeSessions;

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
    InitializeTenancyBySubdomain::class,
    PreventAccessFromUnwantedDomains::class,
    ScopeSessions::class,
])->group(function (): void {

    Route::post('loginLink', LoginLinkController::class)->name('loginLink');

    Route::redirect('/', 'dashboard')->name('home');
    Route::get('/impersonate/{token}', ImpersonateUserController::class)->name('impersonate');

    Route::middleware('auth:tenant')->group(function (): void {
        // This route is used to set session variables for the application
        Route::post('session', SessionController::class)->name('session');

        Route::get('dashboard', DashboardController::class)->name('dashboard');

        require __DIR__.'/tenant/main.php';
        require __DIR__.'/tenant/reports.php';
        require __DIR__.'/tenant/codes.php';
        require __DIR__.'/tenant/accounting.php';
        require __DIR__.'/tenant/settings.php';
        require __DIR__.'/tenant/communication.php';
    });

    require __DIR__.'/tenant/auth.php';

});
