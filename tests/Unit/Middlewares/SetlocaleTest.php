<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

test('valid locale sets application locale and URL defaults', function (): void {

    $request = Request::create(route('home', ['locale' => 'en']));

    $next = fn () => response('Page');

    $middleware = new App\Http\Middleware\SetLocale();

    $response = $middleware->handle($request, $next);

    expect(app()->getLocale())->toBe('en');
    expect(URL::getDefaultParameters())->toBe(['locale' => 'en']);
    expect($response->getContent())->toBe('Page');

});

test('invalid locale results in 404 response', function (): void {
    $request = Request::create(route('home', ['locale' => 'invalid-locale']));

    $next = fn () => response('Page');

    $middleware = new App\Http\Middleware\SetLocale();

    $response = $middleware->handle($request, $next);
    expect($response->getContent())->not->toBe('Page');

})->throws(
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class
);
