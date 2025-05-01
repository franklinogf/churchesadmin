<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $transaction_id
 * @property-read int $expense_type_id
 * @property-read int|null $member_id
 * @property-read Transaction $transaction
 * @property-read ExpenseType $expenseType
 * @property-read Member|null $member
 * @property-read CarbonImmutable $date
 * @property-read float $amount
 * @property-read string|null $note
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
final class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
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
     * The type of expense.
     *
     * @return BelongsTo<ExpenseType,$this>
     */
    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class);
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
}
