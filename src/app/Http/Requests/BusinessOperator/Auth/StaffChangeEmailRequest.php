<?php

namespace App\Http\Requests\BusinessOperator\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Rules\MatchBusinessOperatorPasswordRule;

class StaffChangeEmailRequest extends FormRequest
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
        $businessId = Auth::guard('business-operator')->user()->business_id;

        return [
            'email'    => ['required', 'string', 'email', 'unique:staff,email'],
            'password' => ['required', 'string', new MatchBusinessOperatorPasswordRule($businessId)],
        ];
    }
}
