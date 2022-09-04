<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Base64 implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (!str_contains($value, ';base64,')) {
            return $fail("The $attribute field is not valid.");
        }
    }
}
