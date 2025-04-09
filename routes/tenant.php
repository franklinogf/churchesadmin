<?php

declare(strict_types=1);

use App\Enums\TenantPermission;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SkillController;
use App\Http\Middleware\SetLocale;
use Illuminate\Auth\Middleware\Authorize;
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

                Route::resource('members', MemberController::class)
                    ->middleware(Authorize::using(TenantPermission::MANAGE_MEMBERS->value));

                Route::resource('skills', SkillController::class)
                    ->except(['show', 'create', 'edit'])
                    ->middleware(Authorize::using(TenantPermission::MANAGE_SKILLS->value));

                Route::resource('categories', CategoryController::class)
                    ->except(['show', 'create', 'edit'])
                    ->middleware(Authorize::using(TenantPermission::MANAGE_CATEGORIES->value));
            });

            require __DIR__.'/settings.php';
            require __DIR__.'/auth.php';

        });

});
