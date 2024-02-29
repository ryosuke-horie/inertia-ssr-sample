<?php

declare(strict_types=1);

namespace App\Rules;

use App\Repositories\AdminStaff\AdminStaffRepositoryInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueAdminStaffEmailHashRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $adminStaffRepository = app()->make(AdminStaffRepositoryInterface::class);
        $adminStaff = $adminStaffRepository->findByEmail($value);

        if (!is_null($adminStaff)) {
            $fail('既に存在するメールアドレスです。');
        }
    }
}
