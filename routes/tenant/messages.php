<?php

declare(strict_types=1);

use App\Http\Controllers\Communication\MemberEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('messages')->name('messages.')->group(function (): void {

    Route::get('members', [MemberEmailController::class, 'index'])->name('members.index');
    Route::get('members/create', [MemberEmailController::class, 'create'])->name('members.create');
    Route::post('members', [MemberEmailController::class, 'store'])->name('members.store');
});
