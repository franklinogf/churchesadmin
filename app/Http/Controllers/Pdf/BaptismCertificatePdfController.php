<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\BaptismCertificate;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class BaptismCertificatePdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(BaptismCertificate $baptismCertificate): Response
    {
        Gate::authorize('print', $baptismCertificate);

        return DomPdf::loadView('pdf.baptism_certificate', [
            'baptismCertificate' => $baptismCertificate,
            'title' => __('Baptism Certificate'),
        ])
            ->stream("baptism_certificate_{$baptismCertificate->id}.pdf");
    }
}
