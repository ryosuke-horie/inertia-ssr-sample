<?php

declare(strict_types=1);

namespace App\Rules;

use App\Repositories\Staff\StaffRepositoryInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueStaffEmailHashRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $staffRepository = app()->make(StaffRepositoryInterface::class);
        $staff = $staffRepository->findByEmail($value);

        if (!is_null($staff)) {
            $fail('既に存在するメールアドレスです。');
        }
    }
}
