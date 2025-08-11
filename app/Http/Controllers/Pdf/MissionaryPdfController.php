<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Enums\PdfGeneratorColumnPosition;
use App\Enums\PdfGeneratorColumnType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pdf\GeneratePdfRequest;
use App\Http\Resources\Missionary\MissionaryResource;
use App\Models\Missionary;
use App\Support\PdfGeneration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

final class MissionaryPdfController extends Controller
{
    public function __construct(private PdfGeneration $pdfGeneration)
    {
        $this->pdfGeneration = new PdfGeneration($this->getColumns());
    }

    public function index(): Response
    {
        $missionaries = Missionary::all();

        return Inertia::render('reports/missionaries', [
            'missionaries' => MissionaryResource::collection($missionaries),
            'columns' => $this->pdfGeneration->getForView(),
        ]);
    }

    public function show(GeneratePdfRequest $request): HttpResponse
    {
        $rowIds = $request->getRows();

        $missionaries = Missionary::query()
            ->when($rowIds !== [], function (Builder $query) use ($rowIds): void {
                $query->whereIn('id', $rowIds);
            })
            ->get();

        return Pdf::loadView('pdf.generated', [
            'title' => __('Missionaries'),
            'rows' => $missionaries,
            'columns' => $this->pdfGeneration->getForPdf($request->getUnSelectedColumns()),
        ])
            ->setPaper($request->getPdfFormat()->value, $request->getPdfOrientation()->value)
            ->stream('missionaries.pdf');

    }

    /**
     * Get the columns to be used for PDF generation.
     *
     * @return array<string,array{label:string,position?:PdfGeneratorColumnPosition,type?:PdfGeneratorColumnType}>
     */
    private function getColumns(): array
    {
        return [
            'name' => ['label' => __('Name')],
            'last_name' => ['label' => __('Last name')],
            'email' => ['label' => __('Email')],
            'phone' => ['label' => __('Phone'), 'position' => PdfGeneratorColumnPosition::CENTER],
            'gender' => ['label' => __('Gender'), 'position' => PdfGeneratorColumnPosition::CENTER, 'type' => PdfGeneratorColumnType::ENUM],
            'church' => ['label' => __('Church')],
            'offering' => ['label' => __('Offering'), 'position' => PdfGeneratorColumnPosition::RIGHT, 'type' => PdfGeneratorColumnType::CURRENCY],
            'offering_frequency' => ['label' => __('Offering frequency'), 'position' => PdfGeneratorColumnPosition::CENTER, 'type' => PdfGeneratorColumnType::ENUM],
        ];
    }
}
