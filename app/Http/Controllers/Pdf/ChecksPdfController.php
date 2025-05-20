<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $pdf = Pdf::loadView('pdf.checks', [
            'checks' => $checks,
        ]);

        return $pdf->stream('checks.pdf');
    }
}
