<?php

declare(strict_types=1);

use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

foreach (config('tenancy.identification.central_domains') as $domain) {
    Route::domain($domain)->group(function (): void {
        Route::get('/', fn () => to_route('root.home', app()->getLocale()))->name('root.index');
        Route::prefix('{locale}')
            ->name('root.')
            ->where(['locale' => '[a-zA-Z]{2}'])
            ->middleware([SetLocale::class])
            ->group(function (): void {
                Route::get('/', fn () => Inertia::render('welcome'))->name('home');

            });
    });
}
