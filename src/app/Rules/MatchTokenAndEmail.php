<?php

namespace App\Rules;

use Closure;
use App\Models\Token;
use Illuminate\Contracts\Validation\ValidationRule;

class MatchTokenAndEmail implements ValidationRule
{
    private ?string $email;

    public function __construct(?string $email)
    {
        $this->email = $email;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($this->email)) {
            $fail('予期せぬエラーが発生しました');
        }

        $hashEmail = hash('sha256', $this->email);
        $where = ['token' => $value,'email' => $hashEmail];
        $tokenRecord = Token::where($where)->first();

        if (is_null($tokenRecord)) {
            $fail('予期せぬエラーが発生しました');
        }
    }
}
