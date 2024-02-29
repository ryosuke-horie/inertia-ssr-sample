<?php

namespace App\Http\Requests\Corporation;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CustomPasswordRule;

class CreateBusinessOperatorRequest extends FormRequest
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
            'businessName'  => ['required', 'max:255'],
            'applicantName' => ['required', 'max:255'],
            'applicantNameKana' => ['required', 'max:255'],
            'phone'             => ['required'],
            'zipCode'           => ['required', 'numeric', 'digits:7'],
            'prefCode'          => ['required'],
            'city'              => ['required', 'max:255'],
            'streetAddress'     => ['required', 'max:255'],
            'building'          => ['required', 'max:255'],
            'email'             => ['required', 'email', 'max:255'],
            // 'password'          => ['required', 'max:255', 'confirmed', new CustomPasswordRule()],
            // 'password_confirmation'   => ['required']
        ];
    }

    public function attributes()
    {
        return [
            'businessName'      => '店舗名',
            'applicantName'     => '担当者氏名',
            'applicantNameKana' => '担当者氏名（カナ）',
            'phone'             => '電話番号',
            'zipCode'           => '郵便番号',
            'prefCode'          => '都道府県',
            'city'              => '市区町村',
            'streetAddress'     => '番地',
            'building'          => '建物名など',
            'email'             => 'メールアドレス',
            'password'          => 'パスワード',
            'password_confirmation'   => 'パスワード確認',
        ];
    }
}
