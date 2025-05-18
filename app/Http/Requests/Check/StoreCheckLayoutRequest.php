<?php

declare(strict_types=1);

namespace App\Http\Requests\Check;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCheckLayoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'wallet_id' => ['required', 'integer', 'exists:church_wallets,id'],
            'image' => ['required', 'image', 'max:2048'],
            'width' => ['required', 'integer', 'min:1'],
            'height' => ['required', 'integer', 'min:1'],
        ];
    }
}
