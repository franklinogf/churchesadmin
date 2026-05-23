<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\MarriageCertificate;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class MarriageCertificatePdfController extends Controller
{
    public function __invoke(MarriageCertificate $marriageCertificate): Response
    {
        Gate::authorize('print', $marriageCertificate);

        return DomPdf::loadView('pdf.marriage_certificate', [
            'marriageCertificate' => $marriageCertificate,
            'title' => __('Marriage Certificate'),
        ])
            ->stream("marriage_certificate_{$marriageCertificate->id}.pdf");
    }
}
