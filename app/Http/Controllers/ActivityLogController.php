<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\TenantPermission;
use App\Http\Resources\Activity\ActivityResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

final class ActivityLogController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize(TenantPermission::ACTIVITY_LOGS_MANAGE->value);

        $activityLogs = Activity::query()
            ->when($request->filled('log_name'), fn (Builder $q): Builder => $q->where('log_name', $request->string('log_name')))
            ->when($request->filled('start_date'), fn (Builder $q): Builder => $q->whereDate('created_at', '>=', $request->date('start_date')))
            ->when($request->filled('end_date'), fn (Builder $q): Builder => $q->whereDate('created_at', '<=', $request->date('end_date')))
            ->with(['subject', 'causer'])
            ->orderByDesc('created_at')
            ->get();

        // Get unique log names for the filter dropdown
        $logNames = Activity::query()
            ->select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->orderBy('log_name')
            ->pluck('log_name');

        return Inertia::render('activity-logs/index', [
            'activityLogs' => ActivityResource::collection($activityLogs),
            'logNames' => $logNames,
            'filters' => $request->only(['log_name', 'start_date', 'end_date']),
        ]);
    }
}
