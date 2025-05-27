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
        $key = 'send-email-rate-limit';

        // Only allow 2 hits per second
        if (RateLimiter::tooManyAttempts($key, 2)) {
            $job->release(rand(2, 5)); // Retry after 2 to 5 seconds

            return;
        }

        RateLimiter::hit($key, 1); // lockout = 1 second

        $next($job);
    }
}
