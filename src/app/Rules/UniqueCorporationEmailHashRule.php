<?php

namespace App\Rules;

use Closure;
use App\Repositories\Corporation\CorporationRepositoryInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class UniqueCorporationEmailHashRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $corporationRepository = app()->make(CorporationRepositoryInterface::class);

        $emailHash = hash('sha256', $value);

        $corporation = $corporationRepository->findByEmailHash($emailHash);

        if (!is_null($corporation)) {
            $fail('既に存在するメールアドレスです。');
        }
    }
}
