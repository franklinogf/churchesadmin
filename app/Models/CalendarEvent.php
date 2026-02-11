<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CalendarEvent model.
 *
 * @property-read int $id
 * @property-read string $title
 * @property-read string|null $description
 * @property-read string|null $location
 * @property-read CarbonImmutable $start_at
 * @property-read CarbonImmutable $end_at
 * @property-read int $created_by
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read CarbonImmutable|null $deleted_at
 * @property-read TenantUser $creator
 */
final class CalendarEvent extends Model
{
    /** @use HasFactory<\Database\Factories\CalendarEventFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The user who created this event.
     *
     * @return BelongsTo<TenantUser, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(TenantUser::class, 'created_by');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_at' => 'immutable_datetime',
            'end_at' => 'immutable_datetime',
        ];
    }
}
