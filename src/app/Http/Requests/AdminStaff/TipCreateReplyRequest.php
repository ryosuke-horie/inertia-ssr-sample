<?php

declare(strict_types=1);

namespace App\Http\Requests\AdminStaff;

use App\Rules\FileRule;
use Illuminate\Foundation\Http\FormRequest;

class TipCreateReplyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:100'],
            'file'    => ['nullable', 'mimes:jpeg,png,jpg,mp4', new FileRule()]
        ];
    }
}
