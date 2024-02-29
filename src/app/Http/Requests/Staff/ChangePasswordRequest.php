<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use App\Rules\CustomPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
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
            'password' => ['required', 'confirmed', new CustomPasswordRule(), Password::defaults()]
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
