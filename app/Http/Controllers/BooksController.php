<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BaptismCertificate;
use App\Models\ConfirmationCertificate;
use App\Models\FirstCommunionCertificate;
use App\Models\MarriageCertificate;
use App\Models\Negativa;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class BooksController extends Controller
{
    public function __invoke(): Response
    {
        Gate::authorize('viewAny', BaptismCertificate::class);

        return Inertia::render('main/books/index', [
            'baptismCount' => BaptismCertificate::count(),
            'confirmationCount' => ConfirmationCertificate::count(),
            'marriageCount' => MarriageCertificate::count(),
            'communionCount' => FirstCommunionCertificate::count(),
            'negativaCount' => Negativa::count(),
        ]);
    }
}
