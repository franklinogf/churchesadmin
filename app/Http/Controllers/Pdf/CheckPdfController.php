<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class CheckPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Check $check): Response
    {
        Gate::authorize('print', $check);
        $pdf = DomPdf::loadView('pdf.check', [
            'check' => $check,
            'title' => __('Check #:number', ['number' => $check->check_number ?? '']),
        ]);

        return $pdf->stream("check_{$check->check_number}.pdf");
    }
}
