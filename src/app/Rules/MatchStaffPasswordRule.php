<?php

namespace App\Rules;

use Closure;
use App\Repositories\Staff\StaffRepositoryInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class MatchStaffPasswordRule implements ValidationRule
{
    private int $staffId;

    public function __construct(int $staffId)
    {
        $this->staffId = $staffId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $staffRepository = app()->make(StaffRepositoryInterface::class);

        $staff = $staffRepository->findByStaffId($this->staffId);

        if (!Hash::check($value, $staff->password)) {
            $fail('パスワードが一致しません。');
        }
    }
}
