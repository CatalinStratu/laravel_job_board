<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmptyifRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->validate($data, [ "entry" => [ new EmptyifRule() ] ]);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        Validator::replacer('empty_if', function($message, $attribute, $rule, $parameters){
            $replace = [$attribute, $parameters[0]];
            //message is: The field :attribute cannot be filled if :other is also filled
            return  str_replace([':attribute', ':other'], $replace, $message);
        });
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute should be a year of Olympic Games';
    }
}
