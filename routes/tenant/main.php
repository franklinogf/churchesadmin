<?php

declare(strict_types=1);

use App\Http\Controllers\BaptismCertificateController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConfirmationCertificateController;
use App\Http\Controllers\FirstCommunionCertificateController;
use App\Http\Controllers\MarriageCertificateController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberStatusController;
use App\Http\Controllers\MissionaryController;
use App\Http\Controllers\NegativaController;
use App\Http\Controllers\Pdf\BaptismCertificatePdfController;
use App\Http\Controllers\Pdf\CalendarEventPdfController;
use App\Http\Controllers\Pdf\ConfirmationCertificatePdfController;
use App\Http\Controllers\Pdf\FirstCommunionCertificatePdfController;
use App\Http\Controllers\Pdf\MarriageCertificatePdfController;
use App\Http\Controllers\Pdf\NegativaPdfController;
use App\Http\Controllers\SendCalendarEventToMembersController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitFollowUpController;
use Illuminate\Support\Facades\Route;

Route::resource('skills', SkillController::class)
    ->parameter('skills', 'tag')
    ->except(['show', 'create', 'edit']);

Route::get('books', BooksController::class)->name('books.index');

Route::resource('books/baptism', BaptismCertificateController::class)
    ->parameters(['baptism' => 'baptismCertificate'])
    ->except(['show'])
    ->names([
        'index' => 'books.baptism.index',
        'create' => 'books.baptism.create',
        'store' => 'books.baptism.store',
        'edit' => 'books.baptism.edit',
        'update' => 'books.baptism.update',
        'destroy' => 'books.baptism.destroy',
    ]);
Route::get('books/baptism/{baptismCertificate}/pdf', BaptismCertificatePdfController::class)
    ->name('books.baptism.pdf');

Route::resource('books/confirmation', ConfirmationCertificateController::class)
    ->parameters(['confirmation' => 'confirmationCertificate'])
    ->except(['show'])
    ->names([
        'index' => 'books.confirmation.index',
        'create' => 'books.confirmation.create',
        'store' => 'books.confirmation.store',
        'edit' => 'books.confirmation.edit',
        'update' => 'books.confirmation.update',
        'destroy' => 'books.confirmation.destroy',
    ]);
Route::get('books/confirmation/{confirmationCertificate}/pdf', ConfirmationCertificatePdfController::class)
    ->name('books.confirmation.pdf');

Route::resource('books/marriage', MarriageCertificateController::class)
    ->parameters(['marriage' => 'marriageCertificate'])
    ->except(['show'])
    ->names([
        'index' => 'books.marriage.index',
        'create' => 'books.marriage.create',
        'store' => 'books.marriage.store',
        'edit' => 'books.marriage.edit',
        'update' => 'books.marriage.update',
        'destroy' => 'books.marriage.destroy',
    ]);
Route::get('books/marriage/{marriageCertificate}/pdf', MarriageCertificatePdfController::class)
    ->name('books.marriage.pdf');

Route::resource('books/communion', FirstCommunionCertificateController::class)
    ->parameters(['communion' => 'firstCommunionCertificate'])
    ->except(['show'])
    ->names([
        'index' => 'books.communion.index',
        'create' => 'books.communion.create',
        'store' => 'books.communion.store',
        'edit' => 'books.communion.edit',
        'update' => 'books.communion.update',
        'destroy' => 'books.communion.destroy',
    ]);
Route::get('books/communion/{firstCommunionCertificate}/pdf', FirstCommunionCertificatePdfController::class)
    ->name('books.communion.pdf');

Route::resource('books/negativa', NegativaController::class)
    ->parameters(['negativa' => 'negativa'])
    ->except(['show'])
    ->names([
        'index' => 'books.negativa.index',
        'create' => 'books.negativa.create',
        'store' => 'books.negativa.store',
        'edit' => 'books.negativa.edit',
        'update' => 'books.negativa.update',
        'destroy' => 'books.negativa.destroy',
    ]);
Route::get('books/negativa/{negativa}/pdf', NegativaPdfController::class)
    ->name('books.negativa.pdf');

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
