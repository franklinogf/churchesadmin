<?php

declare(strict_types=1);

namespace App\Http\Resources\Expense;

use App\Http\Resources\Codes\ExpenseTypeResource;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Wallet\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Expense
 */
final class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'transaction' => new TransactionResource($this->transaction),
            'member' => $this->member ? new MemberResource($this->member) : null,
            'expenseType' => new ExpenseTypeResource($this->expenseType),
            'note' => $this->note,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
