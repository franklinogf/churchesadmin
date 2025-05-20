<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Check;
use App\Models\ChurchWallet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class CheckPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Check $check): Response
    {
        /**
         * @var ChurchWallet $wallet
         */
        $wallet = $check->transaction->wallet->holder;

        $checkLayout = $wallet->checkLayout;

        if ($checkLayout === null) {
            abort(404);
        }

        $pdf = Pdf::loadView('pdf.check', [
            'fields' => $check->fields->toArray(),
            'checkLayout' => $checkLayout,
        ])->setPaper([0, 0, $checkLayout->width * 0.75, $checkLayout->height * 0.75]);

        return $pdf->stream();
    }
}
