<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'staffName' => ['required', 'string', 'max:255'],
            'comment'   => ['max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'staffName' => 'ニックネーム',
            'comment'   => '一言コメント'
        ];
    }
}
