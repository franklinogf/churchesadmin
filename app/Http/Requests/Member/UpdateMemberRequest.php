<?php

declare(strict_types=1);

namespace App\Http\Requests\Member;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property-read \App\Models\Member $member
 */
final class UpdateMemberRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::unique('members')->ignore($this->member->id)],
            'phone' => ['required', 'phone', Rule::unique('members')->ignore($this->member->id)],
            'gender' => ['required', 'string', Rule::enum(Gender::class)],
            'dob' => ['required', 'date:Y-m-d'],
            'civil_status' => ['required', 'string', Rule::enum(CivilStatus::class)],
            'skills' => ['array'],
            'skills.*' => ['string'],
            'categories' => ['array'],
            'categories.*' => ['string'],
            'address' => ['exclude_if:address.address_1,null'],
            'address.address_1' => ['required_with:address.city,address.state,address.zip_code,address.country', 'nullable', 'string', 'min:2', 'max:255'],
            'address.address_2' => ['nullable', 'string', 'min:2', 'max:255'],
            'address.city' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.state' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.zip_code' => ['required_unless:address.address_1,null', 'nullable', 'string', 'min:2', 'max:255'],
            'address.country' => ['required_unless:address.address_1,null', 'nullable', 'string', 'uppercase', 'min:2', 'max:2'],
        ];
    }

    public function getMemberData(): array
    {
        return $this->safe()->except(['skills', 'categories', 'address']);
    }

    public function getSkillData(): array
    {
        return collect($this->safe()->only(['skills']))->flatten()->toArray();
    }

    public function getCategoryData(): array
    {
        return collect($this->safe()->only(['categories']))->flatten()->toArray();
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
