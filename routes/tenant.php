<?php

declare(strict_types=1);

use App\Models\Tenant;
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
    Route::get('/', function (): string {

        echo tenant_asset('foto.jpg');
        echo '<br>';

        return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id')."\n";
    })->name('tenant');
});
