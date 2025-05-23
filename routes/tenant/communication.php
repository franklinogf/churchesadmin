<?php

declare(strict_types=1);

use App\Http\Controllers\Communication\EmailController;
use App\Http\Controllers\Communication\EmailListMemberController;
use Illuminate\Support\Facades\Route;

Route::prefix('communication')->name('communication.')->group(function (): void {

    Route::prefix('emails')->name('emails.')->group(function (): void {
        Route::get('/', [EmailController::class, 'index'])->name('index');
        Route::get('create', [EmailController::class, 'create'])->name('create');
        Route::post('store', [EmailController::class, 'store'])->name('store');

        Route::get('members-list', EmailListMemberController::class)->name('members');
        // Route::get('members/create', [EmailMemberController::class, 'create'])->name('members.create');
        // Route::post('members', [EmailMemberController::class, 'store'])->name('members.store');
    });

});
