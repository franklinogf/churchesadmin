<?php

declare(strict_types=1);

namespace App\Jobs\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;

final class EmailRateLimiter
{
    /**
     * Process the queued job.
     *
     * @param  Closure(object): void  $next
     */
    public function handle(object $job, Closure $next): void
    {
        RateLimiter::attempt('email', 120, fn () => $next($job), 60);
    }
}
