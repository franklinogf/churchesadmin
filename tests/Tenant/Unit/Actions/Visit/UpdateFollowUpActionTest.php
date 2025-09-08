<?php

declare(strict_types=1);

namespace Tests\Feature\HTTP\Tenant\Visit\Actions;

use App\Actions\Visit\UpdateFollowUpAction;
use App\Enums\FollowUpType;
use App\Models\FollowUp;
use App\Models\Member;
use App\Models\Visit;

beforeEach(function (): void {
    $this->visit = Visit::factory()->create();
    $this->member = Member::factory()->create();

    $this->followUp = FollowUp::factory()->create([
        'visit_id' => $this->visit->id,
        'member_id' => $this->member->id,
        'type' => FollowUpType::CALL,
        'follow_up_at' => '2025-06-01 10:00',
        'notes' => 'Initial notes',
    ]);

    $this->newMember = Member::factory()->create();
});

it('can update all follow-up fields', function (): void {

    $data = [
        'member_id' => $this->newMember->id,
        'type' => FollowUpType::IN_PERSON,
        'follow_up_at' => '2025-06-05 15:00',
        'notes' => 'Updated notes',
    ];

    $action = new UpdateFollowUpAction();
    $updatedFollowUp = $action->handle($this->followUp, $data);

    expect($updatedFollowUp)->toBeInstanceOf(FollowUp::class)
        ->and($updatedFollowUp->visit_id)->toBe($this->visit->id)
        ->and($updatedFollowUp->member_id)->toBe($this->newMember->id)
        ->and($updatedFollowUp->type)->toBe(FollowUpType::IN_PERSON)
        ->and($updatedFollowUp->follow_up_at->format('Y-m-d H:i'))->toBe('2025-06-05 15:00')
        ->and($updatedFollowUp->notes)->toBe('Updated notes');
});

it('can update partial follow-up fields', function (): void {

    $data = [
        'type' => FollowUpType::LETTER,
    ];

    $action = new UpdateFollowUpAction();
    $updatedFollowUp = $action->handle($this->followUp, $data);

    expect($updatedFollowUp)->toBeInstanceOf(FollowUp::class)
        ->and($updatedFollowUp->visit_id)->toBe($this->visit->id)
        ->and($updatedFollowUp->member_id)->toBe($this->member->id)
        ->and($updatedFollowUp->type)->toBe(FollowUpType::LETTER)
        ->and($updatedFollowUp->follow_up_at->format('Y-m-d H:i'))->toBe('2025-06-01 10:00')
        ->and($updatedFollowUp->notes)->toBe('Initial notes');
});

it('can update notes to null', function (): void {

    $data = [
        'notes' => null,
    ];

    $action = new UpdateFollowUpAction();
    $updatedFollowUp = $action->handle($this->followUp, $data);

    expect($updatedFollowUp)->toBeInstanceOf(FollowUp::class)
        ->and($updatedFollowUp->notes)->toBeNull();
});
