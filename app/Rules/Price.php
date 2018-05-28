<?php 
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Price implements Rule
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
        return preg_match('/^([1-9][0-9]{0,2})(\s?\d{3})*?(\.[0-9]{2}|\,[0-9]{2})?$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be uppercase.';
    }
}