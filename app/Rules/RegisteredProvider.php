<?php

namespace App\Rules;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Contracts\Validation\InvokableRule;

class RegisteredProvider implements InvokableRule
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
        return User::exists($value) || Seller::exists($value);
    }
}
