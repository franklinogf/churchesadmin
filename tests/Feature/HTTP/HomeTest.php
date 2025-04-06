<?php

declare(strict_types=1);

use function Pest\Laravel\get;

test('redirects to locale home page', function () {
    get(route('root.index'))
        ->assertRedirect(route('root.home', ['locale' => app()->getLocale()]));

    expect(tenant())->toBe(null);

});

test('home page is accessible', function () {
    get(route('root.home'))
        ->assertOk();

    expect(tenant())->toBe(null);
});
