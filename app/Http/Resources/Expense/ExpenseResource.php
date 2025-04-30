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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'transaction' => new TransactionResource($this->transaction),
            'member' => $this->member ? new MemberResource($this->member) : null,
            'expenseType' => new ExpenseTypeResource($this->expenseType),
            'note' => $this->note,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
