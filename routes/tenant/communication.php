<?php

declare(strict_types=1);

use App\Http\Controllers\Communication\EmailController;
use App\Http\Controllers\Communication\EmailListMemberController;
use App\Http\Controllers\Communication\EmailListMissionaryController;
use Illuminate\Support\Facades\Route;

Route::prefix('communication')->name('communication.')->group(function (): void {

    Route::get('emails/members', EmailListMemberController::class)->name('emails.members');
    Route::get('emails/missionaries', EmailListMissionaryController::class)->name('emails.missionaries');
    Route::resource('emails', EmailController::class)
        ->except(['destroy', 'edit', 'update']);

});
