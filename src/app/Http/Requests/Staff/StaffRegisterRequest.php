<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use App\Rules\CustomPasswordRule;
use App\Rules\MatchTokenAndEmail;
use App\Rules\UniqueStaffEmailHashRule;
use Illuminate\Foundation\Http\FormRequest;

class StaffRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $email = $this->input('email');

        return [
            'staffName' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', new UniqueStaffEmailHashRule()],
            'token'     => ['required', new MatchTokenAndEmail($email)],
            'password'  => ['required', 'string', 'confirmed', new CustomPasswordRule()]
        ];
    }
}
