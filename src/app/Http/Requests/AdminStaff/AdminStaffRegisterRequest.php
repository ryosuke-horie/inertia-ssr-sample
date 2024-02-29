<?php

declare(strict_types=1);

namespace App\Http\Requests\AdminStaff;

use App\Rules\CustomPasswordRule;
use App\Rules\MatchTokenAndEmail;
use App\Rules\UniqueAdminStaffEmailHashRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminStaffRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $email = request('email');

        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', new UniqueAdminStaffEmailHashRule()],
            'token'     => ['required', new MatchTokenAndEmail($email)],
            'staffIds'  => ['required', 'array'],
            'password'  => ['required', 'confirmed', new CustomPasswordRule(), ]
        ];
    }
}
