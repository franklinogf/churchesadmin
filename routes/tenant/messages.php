<?php

declare(strict_types=1);

use App\Http\Controllers\Communication\MemberMessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('messages')->name('messages.')->group(function (): void {

    Route::get('members', [MemberMessageController::class, 'index'])->name('members.index');
    Route::get('members/create', [MemberMessageController::class, 'create'])->name('members.create');
});
