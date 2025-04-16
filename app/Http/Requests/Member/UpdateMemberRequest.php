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

    /**
     * Get the validated member data from the request.
     *
     * @return array<string,mixed>
     */
    public function getMemberData(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->safe()->except(['skills', 'categories', 'address']);

        return $data;
    }

    /**
     * Get the validated skills data from the request.
     *
     * @return array<int,string>
     */
    public function getSkillData(): array
    {
        /**
         * @var array<int,string> $data
         */
        $data = collect($this->safe()->only('skills'))->flatten()->toArray();

        return $data;
    }

    /**
     * Get the validated category data from the request.
     *
     * @return array<int,string>
     */
    public function getCategoryData(): array
    {
        /**
         * @var array<int,string> $data
         */
        $data = collect($this->safe()->only('categories'))->flatten()->toArray();

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
