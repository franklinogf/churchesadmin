<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Http\Controllers\Controller;
use App\Http\Resources\Visit\VisitResource;
use App\Models\Visit;
use Inertia\Inertia;
use Inertia\Response;

final class EmailListVisitorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $visitors = Visit::query()->whereNotNull('email')->get();

        return Inertia::render('communication/emails/visitors', [
            'visitors' => VisitResource::collection($visitors),
        ]);
    }
}
