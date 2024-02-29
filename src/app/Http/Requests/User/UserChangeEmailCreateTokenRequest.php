<?php

namespace App\Http\Requests\User;

use App\Rules\UniqueUserEmailHashRule;
use Illuminate\Foundation\Http\FormRequest;

class UserChangeEmailCreateTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email', new UniqueUserEmailHashRule()],
            'password' => ['required', 'current_password'],
        ];
    }
}
