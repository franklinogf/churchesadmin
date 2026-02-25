<?php

declare(strict_types=1);

namespace App\Http\Resources\Check;

use App\Http\Resources\Codes\ExpenseTypeResource;
use App\Http\Resources\Member\MemberResource;
use App\Http\Resources\Wallet\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin \App\Models\Check
 */
final class CheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'member' => new MemberResource($this->member),
            'transaction' => new TransactionResource($this->transaction),
            'expenseType' => new ExpenseTypeResource($this->expenseType),
            'note' => $this->note,
            'checkNumber' => $this->check_number,
            'date' => $this->date->format('Y-m-d'),
            'type' => $this->type,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
