<?php

declare(strict_types=1);

namespace Tests\Feature\HTTP\Tenant\Visit\Actions;

use App\Actions\Visit\CreateFollowUpAction;
use App\Enums\FollowUpType;
use App\Models\FollowUp;
use App\Models\Member;
use App\Models\Visit;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

beforeEach(function (): void {
    $this->visit = Visit::factory()->create();
    $this->member = Member::factory()->create();
});

it('can create a follow-up for a visit', function (): void {

    $data = [
        'member_id' => $this->member->id,
        'type' => FollowUpType::CALL,
        'follow_up_at' => '2025-06-01 10:00',
        'notes' => 'Call to check in',
    ];

    $action = new CreateFollowUpAction();
    $followUp = $action->handle($this->visit, $data);

    expect($followUp)->toBeInstanceOf(FollowUp::class)
        ->and($followUp->visit_id)->toBe($this->visit->id)
        ->and($followUp->member_id)->toBe($this->member->id)
        ->and($followUp->type)->toBe(FollowUpType::CALL)
        ->and($followUp->follow_up_at->format('Y-m-d H:i'))->toBe('2025-06-01 10:00')
        ->and($followUp->notes)->toBe('Call to check in');
});

it('can create a follow-up without notes', function (): void {

    $data = [
        'member_id' => $this->member->id,
        'type' => FollowUpType::EMAIL,
        'follow_up_at' => '2025-06-02 14:30',
    ];

    $action = new CreateFollowUpAction();
    $followUp = $action->handle($this->visit, $data);

    expect($followUp)->toBeInstanceOf(FollowUp::class)
        ->and($followUp->visit_id)->toBe($this->visit->id)
        ->and($followUp->member_id)->toBe($this->member->id)
        ->and($followUp->type)->toBe(FollowUpType::EMAIL)
        ->and($followUp->follow_up_at->format('Y-m-d H:i'))->toBe('2025-06-02 14:30')
        ->and($followUp->notes)->toBeNull();
});
