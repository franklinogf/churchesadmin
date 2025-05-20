<?php

declare(strict_types=1);

namespace App\Http\Requests\Check;

use App\Models\Check;
use Closure;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

final class ConfirmMultipleCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('confirm', Check::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'checks' => ['required', 'array'],
            'checks.*.id' => ['required', 'exists:checks,id'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     *
     * @return array<int, Closure>
     */
    public function after(): array
    {

        return [
            function (Validator $validator): void {
                /** @var string[] */
                $checkIds = $this->array('checks.*.id');
                Check::whereIn('id', $checkIds)
                    ->each(function (Check $check) use ($validator): void {
                        if ($check->check_number === null) {
                            $validator->errors()->add(
                                'checks',
                                'All checks must have a check number.'
                            );

                            return;
                        }
                    });

            },
        ];
    }
}
