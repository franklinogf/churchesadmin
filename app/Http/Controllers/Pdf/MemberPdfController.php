<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Enums\PdfGeneratorColumnPosition;
use App\Enums\PdfGeneratorColumnType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pdf\GeneratePdfRequest;
use App\Http\Resources\Member\MemberResource;
use App\Models\Member;
use App\Support\PdfGeneration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

final class MemberPdfController extends Controller
{
    public function __construct(private PdfGeneration $pdfGeneration)
    {
        $this->pdfGeneration = new PdfGeneration($this->getColumns());
    }

    public function index(): Response
    {
        $members = Member::all();

        return Inertia::render('reports/members', [
            'members' => MemberResource::collection($members),
            'columns' => $this->pdfGeneration->getForView(),
        ]);
    }

    public function show(GeneratePdfRequest $request): HttpResponse
    {
        $rowIds = $request->getRows();

        $members = Member::query()
            ->when($rowIds !== [], function (Builder $query) use ($rowIds): void {
                $query->whereIn('id', $rowIds);
            })
            ->get();

        return Pdf::loadView('pdf.generated', [
            'title' => __('Members'),
            'rows' => $members,
            'columns' => $this->pdfGeneration->getForPdf($request->getUnSelectedColumns()),
        ])
            ->setPaper($request->getPdfFormat()->value, $request->getPdfOrientation()->value)
            ->stream('members.pdf');

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
            'dob' => ['label' => __('Date of birth'), 'position' => PdfGeneratorColumnPosition::CENTER, 'type' => PdfGeneratorColumnType::DATE],
            'civil_status' => ['label' => __('Civil status'), 'position' => PdfGeneratorColumnPosition::CENTER, 'type' => PdfGeneratorColumnType::ENUM],
        ];
    }
}
