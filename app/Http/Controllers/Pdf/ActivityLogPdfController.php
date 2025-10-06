<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Enums\TenantPermission;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

final class ActivityLogPdfController extends Controller
{
    public function index(): Response
    {
        Gate::authorize(TenantPermission::ACTIVITY_LOGS_MANAGE->value);

        return Inertia::render('activity-logs/pdf');
    }

    public function show(Request $request): HttpResponse
    {
        Gate::authorize(TenantPermission::ACTIVITY_LOGS_MANAGE->value);

        $activityLogs = Activity::query()
            ->when($request->filled('log_name'), fn (Builder $q): Builder => $q->where('log_name', $request->string('log_name')))
            ->when($request->filled('start_date'), fn (Builder $q): Builder => $q->whereDate('created_at', '>=', $request->date('start_date')))
            ->when($request->filled('end_date'), fn (Builder $q): Builder => $q->whereDate('created_at', '<=', $request->date('end_date')))
            ->with(['subject', 'causer'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get unique log names for the filter dropdown
        $logNames = Activity::query()
            ->select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->orderBy('log_name')
            ->pluck('log_name');

        $title = __('Activity Logs Report');
        if ($request->filled('log_name')) {
            $title .= ' - '.$request->string('log_name');
        }

        if ($request->filled('start_date') || $request->filled('end_date')) {
            $startDate = $request->date('start_date') ? Carbon::parse($request->date('start_date'))->format('M d, Y') : '';
            $endDate = $request->date('end_date') ? Carbon::parse($request->date('end_date'))->format('M d, Y') : '';

            if ($startDate && $endDate) {
                $title .= " ({$startDate} to {$endDate})";
            } elseif ($startDate !== '' && $startDate !== '0') {
                $title .= " (from {$startDate})";
            } elseif ($endDate !== '' && $endDate !== '0') {
                $title .= " (until {$endDate})";
            }
        }

        return Pdf::loadView('pdf.activity_logs', [
            'title' => $title,
            'activityLogs' => $activityLogs,
            'logNames' => $logNames,
        ])
            ->setPaper('letter', 'portrait')
            ->stream('activity_logs_'.now()->format('Y_m_d_H_i_s').'.pdf');
    }
}
