<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Enums\TenantPermission;
use App\Http\Controllers\Controller;
use App\Http\Resources\Activity\ActivityResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

final class ActivityLogPdfController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize(TenantPermission::ACTIVITY_LOGS_MANAGE->value);

        $activityLogs = $this->activityLogs($request);

        $logNames = $this->logNames();

        return Inertia::render('activity-logs/index', [
            'activityLogs' => ActivityResource::collection($activityLogs),
            'logNames' => $logNames,
            'filters' => $request->only(['log_name', 'start_date', 'end_date']),
        ]);
    }

    public function show(Request $request): HttpResponse
    {
        Gate::authorize(TenantPermission::ACTIVITY_LOGS_MANAGE->value);

        $activityLogs = $this->activityLogs($request);

        $logNames = $this->logNames();

        $title = $this->getTitle($request, $logNames);

        return Pdf::loadView('pdf.activity_logs', [
            'title' => $title,
            'activityLogs' => $activityLogs,
            'logNames' => $logNames,
        ])
            ->setPaper('letter', 'portrait')
            ->stream('activity_logs_'.now()->format('Y_m_d_H_i_s').'.pdf');
    }

    /**
     * Generate a dynamic title for the PDF based on filters applied
     *
     * @param  Collection<int|string, mixed>  $logNames
     */
    private function getTitle(Request $request, Collection $logNames): string
    {
        /**
         * @var string
         */
        $title = __('Activity Logs Report');
        if ($request->filled('log_name') && $logNames->contains($request->string('log_name'))) {
            $title .= ' - '.$request->string('log_name');
        }

        if ($request->filled('start_date') || $request->filled('end_date')) {
            $startDate = $request->date('start_date')?->format('Y-m-d');
            $endDate = $request->date('end_date')?->format('Y-m-d');

            $title .= match (true) {
                $startDate && $endDate => ' ('.__(':start_date to :end_date', ['start_date' => $startDate, 'end_date' => $endDate]).')',
                $startDate !== null => ' ('.__('from :date', ['date' => $startDate]).')',
                $endDate !== null => ' ('.__('to :date', ['date' => $endDate]).')',
                default => '',
            };

        }

        return $title;
    }

    /**
     * Get filtered activity logs based on request parameters
     *
     * @return Collection<int, Activity>
     */
    private function activityLogs(Request $request): Collection
    {
        return Activity::query()
            ->when($request->filled('log_name'), fn (Builder $q): Builder => $q->where('log_name', $request->string('log_name')))
            ->when($request->filled('start_date'), fn (Builder $q): Builder => $q->whereDate('created_at', '>=', $request->date('start_date')))
            ->when($request->filled('end_date'), fn (Builder $q): Builder => $q->whereDate('created_at', '<=', $request->date('end_date')))
            ->with(['subject', 'causer'])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get unique log names for the filter dropdown
     *
     * @return Collection<int|string, mixed>
     */
    private function logNames(): Collection
    {
        return Activity::query()
            ->select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->orderBy('log_name')
            ->pluck('log_name');
    }
}
