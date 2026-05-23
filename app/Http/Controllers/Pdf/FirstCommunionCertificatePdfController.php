<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\FirstCommunionCertificate;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class FirstCommunionCertificatePdfController extends Controller
{
    public function __invoke(FirstCommunionCertificate $firstCommunionCertificate): Response
    {
        Gate::authorize('print', $firstCommunionCertificate);

        return DomPdf::loadView('pdf.first_communion_certificate', [
            'firstCommunionCertificate' => $firstCommunionCertificate,
            'title' => __('First Communion Certificate'),
        ])
            ->stream("first_communion_certificate_{$firstCommunionCertificate->id}.pdf");
    }
}
