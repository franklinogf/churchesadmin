<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;

tenant_channel('emails', fn () => Auth::check());
