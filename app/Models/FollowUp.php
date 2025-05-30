<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FollowUpType;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read int $visit_id
 * @property-read Visit $visit
 * @property-read int $member_id
 * @property-read Member $member
 * @property-read FollowUpType $type
 * @property-read CarbonImmutable $follow_up_at
 * @property-read string|null $notes
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read CarbonImmutable|null $deleted_at
 */
final class FollowUp extends Model
{
    /** @use HasFactory<\Database\Factories\FollowUpFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The person who is of the follow-up.
     *
     * @return BelongsTo<Visit,$this>
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * The member associated with the follow-up.
     *
     * @return BelongsTo<Member,$this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => FollowUpType::class,
            'follow_up_at' => 'immutable_datetime:Y-m-d H:i',
        ];
    }
}
