<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Enums\FormFieldValidation as FormFieldValidationTypes;

class FormFieldValidation implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        $rules = array_keys($value);
        $types = FormFieldValidationTypes::getValues();

        return count(array_diff($rules, $types)) === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return 'Invalid validation rule';
    }
}
