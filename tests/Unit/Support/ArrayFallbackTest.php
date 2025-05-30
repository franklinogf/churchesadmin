<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\ArrayFallback;

describe('ArrayFallback class', function (): void {
    it('returns the value from the array when the key exists', function (): void {
        $data = ['name' => 'John', 'email' => 'john@example.com'];
        $result = ArrayFallback::inputOrFallback($data, 'name', 'Default Name');

        expect($result)->toBe('John');
    });

    it('returns the fallback value when the key does not exist', function (): void {
        $data = ['name' => 'John', 'email' => 'john@example.com'];
        $result = ArrayFallback::inputOrFallback($data, 'age', 30);

        expect($result)->toBe(30);
    });

    it('returns the value from the array even when it is null', function (): void {
        $data = ['name' => 'John', 'age' => null];
        $result = ArrayFallback::inputOrFallback($data, 'age', 30);

        expect($result)->toBeNull();
    });

    it('returns the value from the array even when it is an empty string', function (): void {
        $data = ['name' => 'John', 'description' => ''];
        $result = ArrayFallback::inputOrFallback($data, 'description', 'Default Description');

        expect($result)->toBe('');
    });

    it('handles array values correctly', function (): void {
        $data = ['items' => [1, 2, 3]];
        $result = ArrayFallback::inputOrFallback($data, 'items', [4, 5, 6]);

        expect($result)->toBe([1, 2, 3]);
    });

    it('handles nested arrays with dot notation', function (): void {
        $data = ['user' => ['name' => 'John', 'email' => 'john@example.com']];
        $result = ArrayFallback::inputOrFallback($data, 'user', []);

        expect($result)->toBe(['name' => 'John', 'email' => 'john@example.com']);
    });
});
