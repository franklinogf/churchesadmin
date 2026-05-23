<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Negativa;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class NegativaPdfController extends Controller
{
    public function __invoke(Negativa $negativa): Response
    {
        Gate::authorize('print', $negativa);

        return DomPdf::loadView('pdf.negativa', [
            'negativa' => $negativa,
            'title' => __('Negativa'),
        ])
            ->stream("negativa_{$negativa->id}.pdf");
    }
}
