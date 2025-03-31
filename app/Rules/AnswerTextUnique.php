<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AnswerTextUnique implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $text = array_column($value,'text');
        if(array_unique($text, SORT_REGULAR) !== $text){
            //dd(array_column($value,'text'));
            $fail('Answers in one question must be unique');
        }
    }
}
