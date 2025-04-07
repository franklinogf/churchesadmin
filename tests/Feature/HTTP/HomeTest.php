<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('renders home page', function (): void {
    get(route('root.home'))
        ->assertOk();

    expect(tenant())->toBe(null);
});
