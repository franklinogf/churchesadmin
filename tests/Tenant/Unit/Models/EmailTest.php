<?php

declare(strict_types=1);

use App\Enums\EmailStatus;
use App\Enums\ModelMorphName;
use App\Models\Email;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\TenantUser;
use App\Models\Visit;
use Carbon\CarbonImmutable;

test('to array', function (): void {
    $email = Email::factory()->create()->fresh();

    expect(array_keys($email->toArray()))->toBe([
        'id',
        'subject',
        'body',
        'sender_id',
        'recipients_type',
        'reply_to',
        'status',
        'sent_at',
        'error_message',
        'created_at',
        'updated_at',
    ]);
});

test('casts are applied correctly', function (): void {
    $email = Email::factory()->create()->fresh();

    expect($email->status)->toBeInstanceOf(EmailStatus::class);
    expect($email->recipients_type)->toBeInstanceOf(ModelMorphName::class);

    if ($email->sent_at) {
        expect($email->sent_at)->toBeInstanceOf(CarbonImmutable::class);
    }
});

it('belongs to a sender', function (): void {
    $email = Email::factory()->create()->fresh();

    expect($email->sender)->toBeInstanceOf(TenantUser::class);
    expect($email->sender->id)->toBe($email->sender_id);
});

it('can have member recipients', function (): void {
    $email = Email::factory()->create();
    $members = Member::factory()->count(3)->create();

    $members->each(function ($member) use ($email): void {
        $email->members()->attach($member->id, [
            'status' => EmailStatus::PENDING,
            'sent_at' => null,
        ]);
    });

    expect($email->members)->toHaveCount(3);
    expect($email->members[0])->toBeInstanceOf(Member::class);
});

it('can have missionary recipients', function (): void {
    $email = Email::factory()->create();
    $missionaries = Missionary::factory()->count(2)->create();

    $missionaries->each(function ($missionary) use ($email): void {
        $email->missionaries()->attach($missionary->id, [
            'status' => EmailStatus::PENDING,
            'sent_at' => null,
        ]);
    });

    expect($email->missionaries)->toHaveCount(2);
    expect($email->missionaries[0])->toBeInstanceOf(Missionary::class);
});
it('can have visitor recipients', function (): void {
    $email = Email::factory()->create();
    $visitors = Visit::factory()->count(2)->create();

    $visitors->each(function (Visit $visit) use ($email): void {
        $email->visits()->attach($visit->id, [
            'status' => EmailStatus::PENDING,
            'sent_at' => null,
        ]);
    });

    expect($email->visits)->toHaveCount(2);
    expect($email->visits[0])->toBeInstanceOf(Visit::class);
});

it('implements media interface', function (): void {
    $email = Email::factory()->create();

    expect($email)->toBeInstanceOf(Spatie\MediaLibrary\HasMedia::class);
});

it('can have both member and missionary recipients', function (): void {
    $email = Email::factory()->create();
    $members = Member::factory()->count(2)->create();
    $missionaries = Missionary::factory()->count(1)->create();

    $members->each(function ($member) use ($email): void {
        $email->members()->attach($member->id, [
            'status' => EmailStatus::PENDING,
            'sent_at' => null,
        ]);
    });

    $missionaries->each(function ($missionary) use ($email): void {
        $email->missionaries()->attach($missionary->id, [
            'status' => EmailStatus::PENDING,
            'sent_at' => null,
        ]);
    });

    expect($email->members)->toHaveCount(2);
    expect($email->missionaries)->toHaveCount(1);
});
