<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class PaymentSelectRequest extends FormRequest
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
            'amount' => ['required', 'integer', 'exists:tipping_amount_settings,amount'],
            'freeAmount' => ['required', 'integer','exists:tipping_amount_settings,free_amount'],
            'ageLimitPoint' => ['required', 'integer'],
        ];
    }

    /**
     * 年齢制限バリデーション
     * 20歳：無制限
     * 16~19歳：￥20000
     * 15歳以下：￥5000
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $ageLimitPoint = $this->input('ageLimitPoint');
            $amount = $this->input('amount');

            if ($ageLimitPoint <= $amount) {
                $validator->errors()->add('ageLimitPoint', '年齢制限以上の金額は購入できません。');
            }
        });
    }
}
