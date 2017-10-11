<?php

namespace Runsite\CMF\Validator\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidMethod implements Rule
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
        return str_is('*@*', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('runsite::validation.valid_method');
    }
}