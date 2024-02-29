<?php

namespace App\Http\Requests\BusinessOperator;

use App\Rules\ExistStaffIdByBusinessIdRule;
use App\Rules\UniqueAdminStaffEmailHashRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminStaffCreateRequest extends FormRequest
{
    public function authorize()
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
            'name' => ['required', 'max:255'],
            'email'     => ['required', 'email', new UniqueAdminStaffEmailHashRule()],
            'staffIds'  => ['required', 'array', new ExistStaffIdByBusinessIdRule($businessId)]
        ];
    }

    public function attributes()
    {
        return [
            'name'     => '管理者スタッフ名',
            'email'    => 'メールアドレス',
        ];
    }

    public function messages()
    {
        return [
            'staffIds.array'    => '予期せぬエラーが発生しました',
            'staffIds.required' => '連携スタッフは、必ず１人以上選択してください'
        ];
    }
}
