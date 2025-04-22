<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property-read int $id
 * @property-read int $transaction_id
 * @property-read int $offering_type_id
 * @property-read int|null $donor_id
 * @property-read int|null $recipient_id
 * @property-read string $payment_method
 * @property-read string|null $note
 * @property-read CarbonImmutable $date
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
     * @return BelongsTo<OfferingType,$this>
     */
    public function offering_type(): BelongsTo
    {
        return $this->belongsTo(OfferingType::class, 'offering_type_id');
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
     * The person who the offering is for.
     *
     * @return BelongsTo<Missionary,$this>
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Missionary::class, 'recipient_id');
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
