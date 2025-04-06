<?php

declare(strict_types=1);

use App\Models\Member;
use App\Models\Tag;
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

        $tag = Tag::getTypes();
        dd(app()->getLocale(), $tag);
        // $member = Member::withAnyTags(['laravel'], 'skill')->get();

        // dd($member);

        return 'This is your multi-tenant application. The id of the current tenant is '.tenant('id')."\n";
    })->name('tenant');
});
