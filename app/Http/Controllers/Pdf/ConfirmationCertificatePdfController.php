<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\ConfirmationCertificate;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class ConfirmationCertificatePdfController extends Controller
{
    public function __invoke(ConfirmationCertificate $confirmationCertificate): Response
    {
        Gate::authorize('print', $confirmationCertificate);

        return DomPdf::loadView('pdf.confirmation_certificate', [
            'confirmationCertificate' => $confirmationCertificate,
            'title' => __('Confirmation Certificate'),
        ])
            ->stream("confirmation_certificate_{$confirmationCertificate->id}.pdf");
    }
}
