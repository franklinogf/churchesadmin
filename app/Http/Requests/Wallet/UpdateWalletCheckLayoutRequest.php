<?php

declare(strict_types=1);

namespace App\Http\Requests\Wallet;

use App\Models\ChurchWallet;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

/**
 * @property-read ChurchWallet $wallet
 */
final class UpdateWalletCheckLayoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('updateCheckLayout', $this->wallet);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'check_layout_id' => ['required', 'exists:check_layouts,id'],
        ];
    }
}
