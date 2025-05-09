<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CheckType;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $transaction_id
 * @property-read int $member_id
 * @property-read int $expense_type_id
 * @property-read string|null $check_number
 * @property-read string|null $note
 * @property-read CheckType $type
 * @property-read DateTimeInterface $date
 * @property-read DateTimeInterface $created_at
 * @property-read DateTimeInterface $updated_at
 * @property-read Transaction $transaction
 * @property-read Member $member
 * @property-read ExpenseType $expenseType
 */
final class Check extends Model
{
    /** @use HasFactory<\Database\Factories\CheckFactory> */
    use HasFactory;

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
     * The member that the expense is associated with.
     *
     * @return BelongsTo<Member,$this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * The member that the expense is associated with.
     *
     * @return BelongsTo<ExpenseType,$this>
     */
    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {

        return [
            'date' => 'date:Y-m-d',
            'type' => CheckType::class,
        ];
    }
}
