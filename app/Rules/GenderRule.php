<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GenderRule implements Rule
{
    private $possibleValues;

    public function __construct()
    {
        $this->possibleValues = ['M','F'];
    }

    public function passes($attribute, $value)
    {
        return in_array($value, $this->possibleValues);
    }

    public function message()
    {
        return 'The :attribute field must be one of the following values: '. implode(', ', $this->possibleValues);
    }
}
