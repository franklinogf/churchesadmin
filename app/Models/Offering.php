<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Models\Scopes\CurrentYearScope;
use App\Observers\TransactionalObserver;
use Bavix\Wallet\Models\Transaction;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Override;

/**
 * @property-read int $id
 * @property-read int $transaction_id
 * @property-read int $offering_type_id
 * @property-read string $offering_type_type
 * @property-read int|null $donor_id
 * @property-read PaymentMethod $payment_method
 * @property-read string|null $note
 * @property-read CarbonImmutable $date
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read Transaction $transaction
 * @property-read OfferingType|Missionary $offeringType
 * @property-read Member $donor
 * @property-read CurrentYear $currentYear
 */
#[ScopedBy([CurrentYearScope::class])]
#[ObservedBy([TransactionalObserver::class])]
final class Offering extends Model
{
    /** @use HasFactory<\Database\Factories\OfferingFactory> */
    use HasFactory;

    /**
     * The transaction that the offering is part of.
     *
     * @return BelongsTo<Transaction,$this>
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class)->withoutGlobalScope(CurrentYearScope::class);
    }

    /**
     * The type of offering.
     *
     * @return MorphTo<Model,$this>
     */
    public function offeringType(): MorphTo
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
     * The current year associated with the offering.
     *
     * @return BelongsTo<CurrentYear,$this>
     */
    public function currentYear(): BelongsTo
    {
        return $this->belongsTo(CurrentYear::class, 'current_year_id');
    }

    /**
     * The casts that should be used for the model's attributes.
     *
     * @return array<string,string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'date' => 'immutable_date:Y-m-d',
            'payment_method' => PaymentMethod::class,
        ];
    }
}
