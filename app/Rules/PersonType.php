<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

final class PersonType implements InvokableRule
{
    protected string $personType;

    public function __construct(string $param)
    {
        $this->personType = $param;
    }

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
        if($this->personType === 'fisical' && strlen($value) == 14){
            $fail('If you are a fisical person you must send a CPF not a CNPJ');
        }
        elseif($this->personType === 'legal' && strlen($value) == 11){
            $fail('If you are a legal person you must send a CNPJ not a CPF');
        }
    }
}
