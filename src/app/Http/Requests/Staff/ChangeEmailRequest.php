<?php

declare(strict_types=1);

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchStaffPasswordRule;
use App\Rules\UniqueStaffEmailHashRule;

class ChangeEmailRequest extends FormRequest
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
        $staffId = Auth::guard('staff')->user()->staff_id;

        return [
            'email'    => ['required', 'string', 'email', new UniqueStaffEmailHashRule()],
            'password' => ['required', 'string', new MatchStaffPasswordRule($staffId)],
        ];
    }
}
