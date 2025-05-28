<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Emailable model.
 *
 * @property-read int $id
 * @property-read int $email_id
 * @property-read int $recipient_id
 * @property-read string $recipient_type
 * @property-read Member|Missionary $recipient
 * @property-read Email $email
 * @property-read DateTimeInterface|null $sent_at
 * @property-read EmailStatus $status
 * @property-read string|null $error_message
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 */
final class Emailable extends MorphPivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = 'emailables';

    /**
     * Get the email associated with the emailable.
     *
     * @return BelongsTo<Email, $this>
     */
    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }

    /**
     * Get the recipient of the emailable.
     *
     * @return BelongsTo<Member|Missionary, $this>
     */
    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'status' => EmailStatus::class,
        ];
    }
}
