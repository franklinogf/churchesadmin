<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\CivilStatus;
use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @mixin \App\Models\Member
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
        ];
    }
}
