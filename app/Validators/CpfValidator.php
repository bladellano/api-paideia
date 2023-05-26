<?php

namespace App\Validators;

use Illuminate\Validation\Validator;

class CpfValidator extends Validator
{
    public function validateCpf($attribute, $value)
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (strlen($value) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $value)) {
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $value[$i] * (10 - $i);
        }

        $digit1 = 11 - ($sum % 11);
        if ($digit1 >= 10) {
            $digit1 = 0;
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $value[$i] * (11 - $i);
        }

        $digit2 = 11 - ($sum % 11);
        if ($digit2 >= 10) {
            $digit2 = 0;
        }

        if ($value[9] != $digit1 || $value[10] != $digit2) {
            return false;
        }

        return true;
    }
}
