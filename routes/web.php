<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

foreach (config('tenancy.identification.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            return redirect(app()->getLocale());
        });
        Route::prefix('{locale}')
            ->where(['locale' => '[a-zA-Z]{2}'])
            ->middleware(['setLocale'])
            ->group(function () {

                Route::get('/', fn () => Inertia::render('welcome'))->name('home');

                Route::middleware(['auth', 'verified'])->group(function (): void {
                    Route::get('dashboard', fn () => Inertia::render('dashboard'))->name('dashboard');
                });

                require __DIR__.'/settings.php';
                require __DIR__.'/auth.php';
            });
    });
}
