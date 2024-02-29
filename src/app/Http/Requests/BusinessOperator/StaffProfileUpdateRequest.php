<?php

namespace App\Http\Requests\BusinessOperator;

use Illuminate\Foundation\Http\FormRequest;

class StaffProfileUpdateRequest extends FormRequest
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
            'comment'  => ['max:100'],
        ];
    }
}
