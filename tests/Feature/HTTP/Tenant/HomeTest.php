<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('redirects to locale home page', function (): void {
    get(route('index'))
        ->assertRedirect(app()->getLocale());
});

test('home page is accessible', function (): void {
    get(route('home'))
        ->assertOk();
});
