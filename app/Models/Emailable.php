<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EmailStatus;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

/**
 * Emailable model.
 *
 * @property-read int $id
 * @property-read int $email_id
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
