<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ChecksPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $checkIds = $request->array('checks');
        if (empty($checkIds)) {
            abort(404, 'No checks selected');
        }

        $checks = Check::whereIn('id', $checkIds)->get();

        if ($checks->isEmpty()) {
            abort(404, 'No checks found');
        }

        if ($checks->contains(fn (Check $check): bool => ! $check->isConfirmed())) {
            abort(403, 'Some of the selected checks are not confirmed');
        }

        $pdf = DomPdf::loadView('pdf.checks', [
            'checks' => $checks,
            'title' => __('Checks'),
        ]);

        return $pdf->stream('checks.pdf');
    }
}
