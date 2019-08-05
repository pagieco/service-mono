<?php

namespace App\Rules;

use Ramsey\Uuid\Uuid as UuidValidator;
use Illuminate\Contracts\Validation\Rule;

class Uuid implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return UuidValidator::isValid($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The uuid is not valid.';
    }
}
