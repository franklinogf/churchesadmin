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
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! isset($value['model'])) {
            $fail('The model is required for the :attribute field.');
        }

        if (! isset($value['id'])) {
            $fail('The id is required for the :attribute field.');
        }

        $model = Relation::getMorphedModel($value['model']);

        if ($model === null) {
            $fail('The model does not exist.');
        }

        if ($model !== null) {
            if ($model::where('id', $value['id'])->doesntExist()) {
                $fail('The selected :attribute is invalid.');
            }
        }

    }
}
