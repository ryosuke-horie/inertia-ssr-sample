<?php

namespace App\Http\Requests\BusinessOperator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Rules\CustomPasswordRule;

class BusinessChangePasswordRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'currentPassword' => ['required', 'current_password'],
            'password' => ['required','confirmed', new CustomPasswordRule()]
        ];
    }

    public function attributes()
    {
        return [
            'currentPassword'     => '現在のパスワード',
            'password'            => '新しいパスワード'
        ];
    }
}
