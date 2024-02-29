<?php

namespace App\Http\Requests\BusinessOperator;

use App\Rules\DuplicateEmailsRule;
use App\Rules\UniqueStaffEmailHashRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StaffCreateRequest extends FormRequest
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
        return [
            'staffs' => ['required', 'array', new DuplicateEmailsRule()],
            'staffs.*.staffName' => ['required', 'string', 'max:50'],
            'staffs.*.email'     => ['nullable', 'email', new UniqueStaffEmailHashRule()]
        ];
    }

    public function attributes()
    {
        return [
            'staffs.*.staffName' => 'スタッフ名',
            'staffs.*.email'     => 'メールアドレス',
        ];
    }

    /**
    * @param Validator $validator
    * @return ValidationException
    */
    protected function failedValidation(Validator $validator)
    {
        $errors = [];

        foreach ($validator->errors()->toArray() as $key => $value) {
            $errors = array_merge($errors, [$this->replaceWithUniqueKey($key) => $value[0]]);
        }

        throw ValidationException::withMessages($errors);
    }

    private function replaceWithUniqueKey(string $key): string
    {
        if (preg_match('/staffs\.([0-9]+)/', $key, $match)) {
            $id = $this->all()['staffs'][$match[1]]['id'];
            $pattern = '/staffs\.([0-9]+)/';
            $replace = 'staffs.' . $id;

            return  preg_replace($pattern, $replace, $key);
        }

        return $key;
    }
}
