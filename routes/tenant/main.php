<?php

declare(strict_types=1);

use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberStatusController;
use App\Http\Controllers\MissionaryController;
use App\Http\Controllers\Pdf\CalendarEventPdfController;
use App\Http\Controllers\SendCalendarEventToMembersController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitFollowUpController;
use Illuminate\Support\Facades\Route;

Route::resource('skills', SkillController::class)
    ->parameter('skills', 'tag')
    ->except(['show', 'create', 'edit']);

Route::resource('categories', CategoryController::class)
    ->parameter('categories', 'tag')
    ->except(['show', 'create', 'edit']);

Route::resource('members', MemberController::class);

Route::patch('members/{member}/deactivate', [MemberStatusController::class, 'destroy'])
    ->name('members.deactivate');
Route::patch('members/{member}/activate', [MemberStatusController::class, 'update'])
    ->name('members.activate');

Route::resource('missionaries', MissionaryController::class);
Route::put('missionaries/{missionary}/restore', [MissionaryController::class, 'restore'])
    ->withTrashed()
    ->name('missionaries.restore');
Route::delete('missionaries/{missionary}/force-delete', [MissionaryController::class, 'forceDelete'])
    ->withTrashed()
    ->name('missionaries.forceDelete');

Route::resource('users', UserController::class)
    ->except(['show']);

Route::resource('visits', VisitController::class)->except(['show']);
Route::resource('visits.follow-ups', VisitFollowUpController::class)->except(['edit', 'create', 'show'])->shallow();
Route::put('visits/{visit}/restore', [VisitController::class, 'restore'])
    ->withTrashed()
    ->name('visits.restore');
Route::delete('visits/{visit}/force-delete', [VisitController::class, 'forceDelete'])
    ->withTrashed()
    ->name('visits.forceDelete');

// Calendar Events routes
Route::resource('calendar-events', CalendarEventController::class)
    ->except(['show', 'create', 'edit']);
Route::post('calendar-events/{calendarEvent}', SendCalendarEventToMembersController::class)->name('calendar-events.email.members');

// Calendar Events PDF export routes
Route::get('calendar-events-pdf', [CalendarEventPdfController::class, 'index'])
    ->name('calendar-events.pdf.index');
Route::get('calendar-events-pdf/generate', [CalendarEventPdfController::class, 'show'])
    ->name('calendar-events.pdf.show');
