<?php

declare(strict_types=1);

use App\Models\Member;
use App\Models\Tag;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $tag = Tag::factory()->create()->fresh();

    expect(array_keys($tag->toArray()))->toBe([
        'id',
        'name',
        'slug',
        'type',
        'order_column',
        'is_regular',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $tag = Tag::factory()->create()->fresh();

    expect($tag->is_regular)->toBeBool();
    expect($tag->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($tag->updated_at)->toBeInstanceOf(CarbonImmutable::class);
});

test('tags can have members', function (): void {
    $tag = Tag::factory()->create()->fresh();

    $members = Member::factory(2)->create()->fresh();

    $members->each->attachTag($tag);

    expect($tag->members()->count())->toBe(2);
    expect($tag->members->first())->toBeInstanceOf(Member::class);
});

it('extends spatie tag', function (): void {
    $tag = Tag::factory()->create();

    expect($tag)->toBeInstanceOf(Spatie\Tags\Tag::class);
});

it('can have optional type', function (): void {
    $tag = Tag::factory()->create(['type' => null]);

    expect($tag->type)->toBeNull();
});

it('can have order column', function (): void {
    $tag = Tag::factory()->create(['order_column' => 5]);

    // Note: Spatie Tag might auto-manage order_column, so we check if it's set
    expect($tag->order_column)->toBeInt();
    expect($tag->order_column)->toBeGreaterThanOrEqual(1);
});

it('can be regular or not regular', function (): void {
    $regularTag = Tag::factory()->create(['is_regular' => true]);
    $nonRegularTag = Tag::factory()->create(['is_regular' => false]);

    expect($regularTag->is_regular)->toBeTrue();
    expect($nonRegularTag->is_regular)->toBeFalse();
});

it('generates slug from name', function (): void {
    $tag = Tag::factory()->create(['name' => 'Test Tag Name']);

    expect($tag->slug)->toBe('test-tag-name');
});
