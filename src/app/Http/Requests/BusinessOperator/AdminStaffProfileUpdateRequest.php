<?php

declare(strict_types=1);

namespace App\Http\Requests\BusinessOperator;

use App\Rules\ExistStaffIdByBusinessIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminStaffProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;

        return [
            'name'      => ['required', 'string', 'max:255'],
            'staffIds'  => ['required', 'array', new ExistStaffIdByBusinessIdRule($businessId)]
        ];
    }

    public function attributes()
    {
        return [
            'name'     => '管理者スタッフ名',
            'staffIds' => '連携スタッフ'
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
