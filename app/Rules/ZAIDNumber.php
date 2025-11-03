<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ZAIDNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\d{13}$/', $value)) {
            $fail('The ' . $attribute . ' must be exactly 13 digits.');
            return;
        }

        $yy = substr($value, 0, 2);
        $mm = substr($value, 2, 2);
        $dd = substr($value, 4, 2);

        $currentYear = intval(date('Y'));
        $year = intval($yy) + 1900;

        if ($year > $currentYear) {
            $year -= 100;
        }

        if (!checkdate(intval($mm), intval($dd), $year)) {
            $fail('The ' . $attribute . ' contains an invalid birth date.');
            return;
        }

        if (!$this->isValidLuhn($value)) {
            $fail('The ' . $attribute . ' is not a valid South African ID number.');
        }
    }

    /**
     * Luhn check.
     *
     * @param string $number
     * @return bool
     */
    private function isValidLuhn(string $number): bool
    {
        $sum = 0;
        $alt = false;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $n = intval($number[$i]);

            if ($alt) {
                $n *= 2;
                if ($n > 9) {
                    $n -= 9;
                }
            }

            $sum += $n;
            $alt = !$alt;
        }

        return $sum % 10 === 0;
    }
}
