<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pdf\GeneratePdfRequest;
use App\Http\Resources\Member\MemberResource;
use App\Models\Member;
use App\Support\PdfGeneration;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelPdf\PdfBuilder;

use function Spatie\LaravelPdf\Support\pdf;

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
            'formatOptions' => $this->pdfGeneration->getFormatOptions(),
            'orientationOptions' => $this->pdfGeneration->getOrientationOptions(),
        ]);
    }

    public function show(GeneratePdfRequest $request): PdfBuilder
    {
        $rowIds = $request->getRows();

        $members = Member::query()
            ->when($rowIds !== [], function (Builder $query) use ($rowIds): void {
                $query->whereIn('id', $rowIds);
            })
            ->get();

        return pdf('pdf.generated', [
            'title' => __('Members'),
            'rows' => $members,
            'columns' => $this->pdfGeneration->getForPdf($request->getUnSelectedColumns()),
        ])
            ->orientation($request->getPdfOrientation())
            ->format($request->getPdfFormat())
            ->name('members.pdf');

    }

    /**
     * Get the columns to be used for PDF generation.
     *
     * @return array<string,array{label:string,position?:string}>
     */
    private function getColumns(): array
    {
        return [
            'name' => ['label' => __('Name')],
            'last_name' => ['label' => __('Last name')],
            'email' => ['label' => __('Email')],
            'phone' => ['label' => __('Phone'), 'position' => 'center'],
            'gender' => ['label' => __('Gender'), 'position' => 'center'],
            'dob' => ['label' => __('Date of birth'), 'position' => 'center'],
            'civil_status' => ['label' => __('Civil status'), 'position' => 'center'],
        ];
    }
}
