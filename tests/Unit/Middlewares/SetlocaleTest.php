<?php

declare(strict_types=1);

use Illuminate\Http\Request;

test('valid locale sets application locale and URL defaults', function (): void {

    $request = Request::create(route('home', ['locale' => 'en']));

    $next = fn () => response('Page');

    $middleware = new App\Http\Middleware\SetLocale;

    $response = $middleware->handle($request, $next);

    expect(app()->getLocale())->toBe('en');
    expect($response->getContent())->toBe('Page');

})->skip();

test('invalid locale results in 404 response', function (): void {
    $request = Request::create(route('home', ['locale' => 'invalid-locale']));

    $next = fn () => response('Page');

    $middleware = new App\Http\Middleware\SetLocale;

    $response = $middleware->handle($request, $next);
    expect($response->getContent())->not->toBe('Page');

})->skip()->throws(
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class
);
