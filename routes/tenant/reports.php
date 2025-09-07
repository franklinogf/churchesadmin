<?php

declare(strict_types=1);

use App\Http\Controllers\Pdf\CheckPdfController;
use App\Http\Controllers\Pdf\ChecksPdfController;
use App\Http\Controllers\Pdf\EntriesExpensesPdfController;
use App\Http\Controllers\Pdf\MemberPdfController;
use App\Http\Controllers\Pdf\MissionaryPdfController;
use Illuminate\Support\Facades\Route;

Route::inertia('/reports', 'reports/index')->name('reports');

Route::get('/reports/entries_expenses', [EntriesExpensesPdfController::class, 'index'])->name('reports.entries_expenses');
Route::get('/reports/entries_expenses/pdf', [EntriesExpensesPdfController::class, 'show'])
    ->name('reports.entries_expenses.pdf');

Route::get('/reports/members', [MemberPdfController::class, 'index'])->name('reports.members');
Route::get('/reports/members/pdf', [MemberPdfController::class, 'show'])
    ->name('reports.members.pdf');

Route::get('/reports/missionaries', [MissionaryPdfController::class, 'index'])->name('reports.missionaries');
Route::get('/reports/missionaries/pdf', [MissionaryPdfController::class, 'show'])
    ->name('reports.missionaries.pdf');

// Checks pdf

Route::get('checks/pdf', ChecksPdfController::class)->name('checks.pdf.multiple');
Route::get('checks/{check}/pdf', CheckPdfController::class)->name('checks.pdf');
