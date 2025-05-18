<?php

declare(strict_types=1);

namespace App\Http\Requests\Missionary;

use App\Enums\Gender;
use App\Enums\OfferingFrequency;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read \App\Models\Missionary $missionary
 */
final class UpdateMissionaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->missionary);
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
            'email' => ['nullable', 'email', Rule::unique('missionaries')->ignore($this->missionary->id)],
            'phone' => ['nullable', 'phone', Rule::unique('missionaries')->ignore($this->missionary->id)],
            'gender' => ['required', 'string', Rule::enum(Gender::class)],
            'church' => ['nullable', 'string', 'min:2', 'max:255'],
            'offering' => ['nullable', 'decimal:2', 'min:1'],
            'offering_frequency' => ['nullable', 'string', Rule::enum(OfferingFrequency::class)],
            'address' => ['exclude_if:address.address_1,null', 'array'],
            'address.address_1' => ['required_with:address.city,address.state,address.zip_code,address.country', 'nullable', 'string', 'min:2', 'max:255'],
            'address.address_2' => ['nullable', 'string', 'min:2', 'max:255'],
            'address.city' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.state' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.zip_code' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.country' => ['required_unless:address.address_1,null', 'nullable', 'string', 'uppercase', 'min:2', 'max:2'],
        ];
    }

    /**
     * Get the validated missionary data from the request.
     *
     * @return array<string,mixed>
     */
    public function getMissionaryData(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->safe()->except([
            'address',
        ]);

        return $data;
    }

    /**
     * Get the validated address data from the request.
     *
     * @return array{address_1?: string, address_2?: string, city?: string, state?: string, zip_code?: string, country?: string}|null
     */
    public function getAddressData(): ?array
    {
        /**
         * @var array<string|null, array{
         *     address_1?: string,
         *     address_2?: string,
         *     city?: string,
         *     state?: string,
         *     zip_code?: string,
         *     country?: string
         * }> $data
         */
        $data = $this->safe()->only(['address']);
        if (array_key_exists('address', $data)) {
            return $data['address'];
        }

        return null;
    }
}
