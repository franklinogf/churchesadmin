<?php

declare(strict_types=1);

namespace App\Http\Requests\Missionary;

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreMissionaryRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'last_name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', Rule::unique('missionaries')],
            'phone' => ['required', 'phone', Rule::unique('missionaries')],
            'gender' => ['required', 'string', Rule::enum(Gender::class)],
            'church' => ['required', 'string', 'min:2', 'max:255'],
            'offering' => ['required', 'decimal:2', 'min:1'],
            'offering_frequency' => ['required', 'string', Rule::enum(OfferingFrequency::class)],
            'address' => ['exclude_if:address.address_1,null', 'array'],
            'address.address_1' => ['required_with:address.city,address.state,address.zip_code,address.country', 'nullable', 'string', 'min:2', 'max:255'],
            'address.address_2' => ['nullable', 'string', 'min:2', 'max:255'],
            'address.city' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.state' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.zip_code' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.country' => ['required_unless:address.address_1,null', 'nullable', 'string', 'uppercase', 'min:2', 'max:2'],
        ];
    }

    public function getMissionaryData(): array
    {
        return $this->safe()->except(['address']);
    }

    public function getAddressData(): ?array
    {
        $addressData = $this->safe()->only(['address']);
        if (array_key_exists('address', $addressData)) {
            return $addressData['address'];
        }

        return null;
    }
}
