<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class NotReservedName implements ValidationRule
{
    protected array $reservedWords = [
        'super admin',
        'admin',
        'administrator',
        'owner',
        'system',
        'root',
        'support',
    ];

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        $pattern = '/\b(' . implode('|', array_map('preg_quote', $this->reservedWords)) . ')\b/i';

        if (preg_match($pattern, $value)) {
            $fail('This name is reserved and can\'t be used. Please choose a different name.');
        }
    }
}
