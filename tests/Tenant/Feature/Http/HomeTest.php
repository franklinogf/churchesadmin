<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('home page redirects to dashboard', function (): void {
    get(route('home'))
        ->assertRedirect(route('dashboard'));
});
