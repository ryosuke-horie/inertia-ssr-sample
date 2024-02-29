<?php

namespace App\Rules;

use Closure;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class UniqueBusinessEmailHashRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $businessOperatorRepository = app()->make(BusinessOperatorRepositoryInterface::class);

        $emailHash = hash('sha256', $value);

        $businessOperators = $businessOperatorRepository->findByEmailHash($emailHash);

        if (!is_null($businessOperators)) {
            $fail('既に存在するメールアドレスです。');
        }
    }
}
