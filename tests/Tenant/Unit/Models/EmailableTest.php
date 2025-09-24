<?php

declare(strict_types=1);

use App\Enums\EmailStatus;
use App\Models\Email;
use App\Models\Emailable;
use App\Models\Member;
use App\Models\Missionary;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $emailable = Emailable::factory()->create()->fresh();

    expect(array_keys($emailable->toArray()))->toBe([
        'id',
        'email_id',
        'recipient_type',
        'recipient_id',
        'status',
        'sent_at',
        'error_message',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $emailable = Emailable::factory()->create()->fresh();

    expect($emailable->status)->toBeInstanceOf(EmailStatus::class);
    expect($emailable->created_at)->toBeInstanceOf(CarbonImmutable::class);
    expect($emailable->updated_at)->toBeInstanceOf(CarbonImmutable::class);

    if ($emailable->sent_at) {
        expect($emailable->sent_at)->toBeInstanceOf(CarbonImmutable::class);
    }
});

it('belongs to an email', function (): void {
    $emailable = Emailable::factory()->create()->fresh();

    expect($emailable->email)->toBeInstanceOf(Email::class);
    expect($emailable->email->id)->toBe($emailable->email_id);
});

it('can have member as recipient', function (): void {
    $member = Member::factory()->create();
    $emailable = Emailable::factory()->create([
        'recipient_id' => $member->id,
        'recipient_type' => Member::class,
    ])->fresh();

    expect($emailable->recipient)->toBeInstanceOf(Member::class);
    expect($emailable->recipient->id)->toBe($member->id);
});

it('can have missionary as recipient', function (): void {
    $missionary = Missionary::factory()->create();
    $emailable = Emailable::factory()->create([
        'recipient_id' => $missionary->id,
        'recipient_type' => Missionary::class,
    ])->fresh();

    expect($emailable->recipient)->toBeInstanceOf(Missionary::class);
    expect($emailable->recipient->id)->toBe($missionary->id);
});

it('extends morph pivot', function (): void {
    $emailable = Emailable::factory()->create();

    expect($emailable)->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphPivot::class);
});

it('has auto incrementing ids', function (): void {
    $emailable = new Emailable();

    expect($emailable->incrementing)->toBeTrue();
});

it('can have error message', function (): void {
    $emailable = Emailable::factory()->create([
        'error_message' => 'Test error message',
    ]);

    expect($emailable->error_message)->toBe('Test error message');
});

it('can have null sent_at', function (): void {
    $emailable = Emailable::factory()->create([
        'sent_at' => null,
    ]);

    expect($emailable->sent_at)->toBeNull();
});
