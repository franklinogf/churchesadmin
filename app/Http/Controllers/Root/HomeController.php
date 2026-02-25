<?php

declare(strict_types=1);

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Inertia\Response;

final class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $url = app_url_subdomain('demo');

        return inertia('welcome', ['demoLink' => $url]);
    }
}
