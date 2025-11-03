<?php

declare(strict_types=1);

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Pdf\ActivityLogPdfController;
use App\Http\Controllers\Pdf\CheckPdfController;
use App\Http\Controllers\Pdf\ChecksPdfController;
use App\Http\Controllers\Pdf\ContributionController;
use App\Http\Controllers\Pdf\ContributionPdfController;
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

// Activity Logs
Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
Route::get('/reports/activity-logs', [ActivityLogPdfController::class, 'index'])->name('reports.activity_logs');
Route::get('/reports/activity-logs/pdf', [ActivityLogPdfController::class, 'show'])
    ->name('reports.activity_logs.pdf');

// Checks pdf

Route::get('checks/pdf', ChecksPdfController::class)->name('checks.pdf.multiple');
Route::get('checks/{check}/pdf', CheckPdfController::class)->name('checks.pdf');

Route::get('reports/contributions', ContributionController::class)->name('reports.contributions');
Route::post('report/contributions', [ContributionPdfController::class, 'email'])->name('reports.contributions.email');
Route::get('contributions/{member}/pdf', [ContributionPdfController::class, 'single'])->name('reports.contributions.pdf');
Route::get('contributions/pdf', [ContributionPdfController::class, 'multiple'])->name('reports.contributions.pdf.multiple');
