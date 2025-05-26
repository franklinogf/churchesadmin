<?php

declare(strict_types=1);

namespace App\Http\Controllers\Communication;

use App\Http\Controllers\Controller;
use App\Http\Resources\Missionary\MissionaryResource;
use App\Models\Missionary;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class EmailListMissionaryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $missionaries = Missionary::whereNotNull('email')->get();

        return Inertia::render('communication/emails/missionaries', [
            'missionaries' => MissionaryResource::collection($missionaries),
        ]);
    }
}
