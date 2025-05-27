<?php

declare(strict_types=1);

use App\Http\Controllers\Communication\EmailController;
use App\Http\Controllers\Communication\EmailListMemberController;
use App\Http\Controllers\Communication\EmailListMissionaryController;
use Illuminate\Support\Facades\Route;

Route::prefix('communication')->name('communication.')->group(function (): void {

    Route::prefix('emails')->name('emails.')->group(function (): void {
        Route::get('/', [EmailController::class, 'index'])->name('index');
        Route::get('show/{email}', [EmailController::class, 'show'])->name('show');
        Route::get('create', [EmailController::class, 'create'])->name('create');
        Route::post('store', [EmailController::class, 'store'])->name('store');

        Route::get('members', EmailListMemberController::class)->name('members');
        Route::get('missionaries', EmailListMissionaryController::class)->name('missionaries');
    });

});
