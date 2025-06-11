<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\PdfBuilder;

final class CheckPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Check $check): PdfBuilder
    {
        Gate::authorize('print', $check);
        $pdf = Pdf::view('pdf.check', [
            'check' => $check,
            'title' => __('Check #:number', ['number' => $check->check_number ?? '']),
        ]);

        return $pdf->name("check_{$check->check_number}.pdf");
    }
}
