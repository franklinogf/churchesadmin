<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Relations\Relation;

final class SelectOptionWithModel implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @param  array{model?:mixed,id?:mixed}  $value
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $model = $value['model'] ?? null;
        $id = $value['id'] ?? null;
        if ($model === null) {
            $fail('The model is required for the :attribute field.');
        }

        if ($id === null) {
            $fail('The id is required for the :attribute field.');
        }

        if (! is_string($model)) {
            $fail('The model must be a string.');
        }

        if (empty($model)) {
            $fail('The model must not be empty.');
        }

        if (empty($id)) {
            $fail('The id must not be empty.');
        }

        $modelClass = Relation::getMorphedModel(is_string($model) ? $model : '');

        if ($modelClass === null) {
            $fail('The model does not exist.');
        }

        if ($modelClass !== null && $modelClass::where('id', $id)->doesntExist()) {
            $fail('The selected :attribute is invalid.');
        }

    }
}
