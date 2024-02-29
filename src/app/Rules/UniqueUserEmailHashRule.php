<?php

namespace App\Rules;

use Closure;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueUserEmailHashRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userRepository = app()->make(UserRepositoryInterface::class);
        $user = $userRepository->findByEmail($value);

        if (!is_null($user)) {
            $fail('既に存在するメールアドレスです。');
        }
    }
}
