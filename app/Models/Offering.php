<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class Offering extends Pivot
{
    /** @use HasFactory<\Database\Factories\OfferingFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'offerings';

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function offering_type(): BelongsTo
    {
        return $this->belongsTo(OfferingType::class, 'offering_type_id');
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'donor_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Missionary::class, 'recipient_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }
}
