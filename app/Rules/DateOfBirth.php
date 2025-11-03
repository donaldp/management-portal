<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DateOfBirth implements ValidationRule
{
    /**
     * ID Number.
     *
     * @var string $idNumber
     */
    protected string $idNumber;

    /**
     * Instantiate rule.
     *
     * @param string $idNumber
     */
    public function __construct(string $idNumber)
    {
        $this->idNumber = $idNumber;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dob = \DateTime::createFromFormat('Y-m-d', $value);
        if (!$dob || $dob->format('Y-m-d') !== $value) {
            $fail('The ' . $attribute . ' must be a valid date in YYYY-MM-DD format.');
            return;
        }

        $yy = substr($this->idNumber, 0, 2);
        $mm = substr($this->idNumber, 2, 2);
        $dd = substr($this->idNumber, 4, 2);

        $currentYear = intval(date('Y'));
        $year = intval($yy) + 1900;

        if ($year > $currentYear) {
            $year -= 100;
        }

        $idDob = sprintf('%04d-%02d-%02d', $year, $mm, $dd);

        if ($idDob !== $value) {
            $fail('The date of birth does not match the South African ID number.');
        }
    }
}
