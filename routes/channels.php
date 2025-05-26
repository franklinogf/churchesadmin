<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;

tenant_channel('emails', function (): bool {

    return Auth::check();
});
