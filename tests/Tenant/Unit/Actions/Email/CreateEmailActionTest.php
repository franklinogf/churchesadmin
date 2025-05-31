<?php

declare(strict_types=1);

namespace Tests\Tenant\Unit\Actions\Email;

use App\Actions\Email\CreateEmailAction;
use App\Enums\EmailStatus;
use App\Enums\MediaCollectionName;
use App\Enums\ModelMorphName;
use App\Models\Email;
use App\Models\Member;
use App\Models\Missionary;
use App\Models\TenantUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

it('creates an email successfully with member recipients', function () {
    $user = TenantUser::factory()->create(['email' => 'test@example.com']);

    $members = Member::factory()->count(3)->create();

    $memberIds = $members->pluck('id')->toArray();

    $data = [
        'subject' => 'Test Subject',
        'body' => 'Test Body Content',
    ];
    $action = new CreateEmailAction();

    $email = $action->handle(
        $user,
        $data,
        $memberIds,
        ModelMorphName::MEMBER
    );

    expect($email)->toBeInstanceOf(Email::class)
        ->and($email->subject)->toBe('Test Subject')
        ->and($email->body)->toBe('Test Body Content')
        ->and($email->recipients_type)->toBe(ModelMorphName::MEMBER)
        ->and($email->reply_to)->toBe('test@example.com')
        ->and($email->status)->toBe(EmailStatus::PENDING)
        ->and($email->sender_id)->toBe($user->id);

    expect($email->members)->toHaveCount(3);
    expect($email->missionaries)->toHaveCount(0);

});

it('creates an email successfully with missionary recipients', function () {
    $user = TenantUser::factory()->create(['email' => 'test@example.com']);
    $missionaries = Missionary::factory()->count(2)->create();
    $missionaryIds = $missionaries->pluck('id')->toArray();

    $data = [
        'subject' => 'Test Subject',
        'body' => 'Test Body Content',
    ];
    $action = new CreateEmailAction();

    $email = $action->handle(
        $user,
        $data,
        $missionaryIds,
        ModelMorphName::MISSIONARY
    );

    expect($email)->toBeInstanceOf(Email::class)
        ->and($email->subject)->toBe('Test Subject')
        ->and($email->body)->toBe('Test Body Content')
        ->and($email->recipients_type)->toBe(ModelMorphName::MISSIONARY)
        ->and($email->reply_to)->toBe('test@example.com')
        ->and($email->status)->toBe(EmailStatus::PENDING)
        ->and($email->sender_id)->toBe($user->id);

    expect($email->members)->toHaveCount(0);
    expect($email->missionaries)->toHaveCount(2);
});

it('creates an email with attachments', function () {
    $user = TenantUser::factory()->create();
    $member = Member::factory()->create();
    $data = [
        'subject' => 'Test with Attachment',
        'body' => 'Please find attached document',
    ];
    $file = UploadedFile::fake()->create('document.pdf', 100);
    $action = new CreateEmailAction();

    $email = $action->handle(
        $user,
        $data,
        [$member->id],
        ModelMorphName::MEMBER,
        [$file]
    );

    expect($email)->toBeInstanceOf(Email::class);

    $this->assertCount(1, $email->getMedia(MediaCollectionName::ATTACHMENT->value));
    $this->assertEquals(MediaCollectionName::ATTACHMENT->value, $email->getMedia(MediaCollectionName::ATTACHMENT->value)->first()->collection_name);
    $this->assertEquals('document.pdf', $email->getMedia(MediaCollectionName::ATTACHMENT->value)->first()->file_name);
});
