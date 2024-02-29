<?php

namespace App\Http\Requests\Inquiry;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required',
            'consent' => 'accepted',
        ];
    }

    /**
     * 属性のカスタマイズ
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'お名前',
            'email' => 'メールアドレス',
            'content' => '内容',
            'consent' => '個人情報の取扱いについて',
        ];
    }

    /**
     * バリデーションエラーメッセージのカスタマイズ
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'content.required' => '内容は必ず入力してください。',
            'consent.accepted' => '個人情報の取扱いについて同意してください。',
        ];
    }
}
