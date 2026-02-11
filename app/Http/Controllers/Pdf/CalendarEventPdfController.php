<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Enums\PdfGeneratorColumnPosition;
use App\Enums\PdfGeneratorColumnType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pdf\GeneratePdfRequest;
use App\Http\Resources\CalendarEventResource;
use App\Models\CalendarEvent;
use App\Support\PdfGeneration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class CalendarEventPdfController extends Controller
{
    public function __construct(private PdfGeneration $pdfGeneration)
    {
        $this->pdfGeneration = new PdfGeneration($this->getColumns());
    }

    public function index(Request $request): Response
    {
        Gate::authorize('export', CalendarEvent::class);

        $query = CalendarEvent::query()->with('creator');

        // Filter by date range if provided
        if ($request->has('start_date')) {
            $query->where('start_at', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('end_at', '<=', $request->input('end_date'));
        }

        $events = $query->orderBy('start_at', 'asc')->get();

        return Inertia::render('reports/calendar-events', [
            'events' => CalendarEventResource::collection($events),
            'columns' => $this->pdfGeneration->getForView(),
        ]);
    }

    public function show(GeneratePdfRequest $request): HttpResponse
    {
        Gate::authorize('export', CalendarEvent::class);

        $rowIds = $request->getRows();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = CalendarEvent::query()
            ->when($rowIds !== [], function (Builder $query) use ($rowIds): void {
                $query->whereIn('id', $rowIds);
            })
            ->when($startDate, function (Builder $query, string $startDate): void {
                $query->where('start_at', '>=', $startDate);
            })
            ->when($endDate, function (Builder $query, string $endDate): void {
                $query->where('end_at', '<=', $endDate);
            });

        $events = $query->orderBy('start_at', 'asc')->get();

        return Pdf::loadView('pdf.generated', [
            'title' => __('Calendar Events'),
            'rows' => $events,
            'columns' => $this->pdfGeneration->getForPdf($request->getUnSelectedColumns()),
        ])
            ->setPaper($request->getPdfFormat()->value, $request->getPdfOrientation()->value)
            ->stream('calendar-events.pdf');
    }

    /**
     * Get the columns to be used for PDF generation.
     *
     * @return array<string,array{label:string,position?:PdfGeneratorColumnPosition,type?:PdfGeneratorColumnType}>
     */
    private function getColumns(): array
    {
        return [
            'title' => ['label' => __('Title')],
            'description' => ['label' => __('Description')],
            'location' => ['label' => __('Location')],
            'start_at' => ['label' => __('Start'), 'position' => PdfGeneratorColumnPosition::CENTER, 'type' => PdfGeneratorColumnType::DATE_TIME],
            'end_at' => ['label' => __('End'), 'position' => PdfGeneratorColumnPosition::CENTER, 'type' => PdfGeneratorColumnType::DATE_TIME],
        ];
    }
}
