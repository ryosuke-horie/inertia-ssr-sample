<?php

namespace App\Http\Requests\BusinessOperator;

use App\Rules\ExistStaffIdByBusinessIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StaffCreateAdminStaffRequest extends FormRequest
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
            'name' => ['required', 'max:50'],
            'email'     => ['required', 'email', 'unique:admin_staff,email'],
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
            'name.required'     => '管理者スタッフ名は、必ず入力してください。',
            'name.max'          => '管理者スタッフ名は、５０文字以内で入力してください。',
            'email.required'    => 'メールアドレスは、必ず入力してください。',
            'email.email'       => '有効なメールアドレスを入力してください。',
            'email.unique'      => '既に登録されているメールアドレスです。',
            'staffIds.array'    => '予期せぬエラーが発生しました',
            'staffIds.required' => '連携スタッフは、必ず１人以上選択してください'
        ];
    }

    // /**
    // * @param Validator $validator
    // * @return ValidationException
    // */
    // protected function failedValidation(Validator $validator)
    // {
    //     $errors = [];

    //     foreach ($validator->errors()->toArray() as $key => $value) {
    //         $errors = array_merge($errors, [$this->replaceWithUniqueKey($key) => $value[0]]);
    //     }

    //     throw ValidationException::withMessages($errors);
    // }

    // private function replaceWithUniqueKey(string $key): string
    // {
    //     if (preg_match('/staff\.([0-9]+)/', $key, $match)) {
    //         $id = $this->all()['staff'][$match[1]]['id'];
    //         $pattern = '/staff\.([0-9]+)/';
    //         $replace = 'staff.' . $id;

    //         return  preg_replace($pattern, $replace, $key);
    //     }

    //     return $key;
    // }
}
