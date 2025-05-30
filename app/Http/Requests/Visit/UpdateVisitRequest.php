<?php

declare(strict_types=1);

namespace App\Http\Requests\Visit;

use App\Models\Visit;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read Visit $visit
 */
final class UpdateVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->visit);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'last_name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['nullable', 'string', 'email', Rule::unique('visits')->ignore($this->visit->id)],
            'phone' => ['required', 'phone', Rule::unique('visits')->ignore($this->visit->id)],
            'first_visit_date' => ['nullable', Rule::date()->format('Y-m-d')->todayOrBefore()],
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
     * Get the validated visit data from the request.
     *
     * @return array{name:string,last_name:string,email:string|null,phone:string,first_visit_date:string|null}
     */
    public function getVisitData(): array
    {
        /** @var array{name:string,last_name:string,email:string|null,phone:string,first_visit_date:string|null} */
        $data = $this->safe()->except(['address']);

        return $data;
    }

    /**
     * Get the validated address data from the request.
     *
     * @return array{address_1: string, address_2: string|null, city: string, state: string, zip_code: string, country: string}|null
     */
    public function getAddressData(): ?array
    {
        /**
         * @var array{
         *     address_1: string,
         *     address_2: string|null,
         *     city: string,
         *     state: string,
         *     zip_code: string,
         *     country: string
         * }|array{} $data
         */
        $data = $this->safe()->array('address');

        if ($data !== []) {
            return $data;
        }

        return null;
    }
}
