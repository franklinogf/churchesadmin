<?php

declare(strict_types=1);

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property-read int $id
 * @property-read int $transaction_id
 * @property-read int $offering_type_id
 * @property-read string $offering_type_type
 * @property-read int|null $donor_id
 * @property-read string $payment_method
 * @property-read string|null $note
 * @property-read DateTimeInterface $date
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 * @property-read Transaction $transaction
 * @property-read OfferingType|Member $offering_type
 * @property-read Member $donor
 */
final class Offering extends Pivot
{
    /** @use HasFactory<\Database\Factories\OfferingFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'offerings';

    /**
     * The transaction that the offering is part of.
     *
     * @return BelongsTo<Transaction,$this>
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * The type of offering.
     *
     * @return MorphTo<Model,$this>
     */
    public function offering_type(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The person who made the offering.
     *
     * @return BelongsTo<Member,$this>
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'donor_id');
    }

    /**
     * The casts that should be used for the model's attributes.
     *
     * @return array{date: string}
     */
    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }
}
