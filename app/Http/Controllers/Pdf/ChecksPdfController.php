<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Check;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\PdfBuilder;

final class ChecksPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): PdfBuilder
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

        $pdf = Pdf::view('pdf.checks', [
            'checks' => $checks,
            'title' => __('Checks'),
        ])->withBrowsershot(function (Browsershot $browsershot): void {
            $browsershot->setChromePath('/usr/bin/chromium-browser')
                ->setCustomTempPath(storage_path());
        });

        return $pdf->name('checks.pdf');
    }
}
