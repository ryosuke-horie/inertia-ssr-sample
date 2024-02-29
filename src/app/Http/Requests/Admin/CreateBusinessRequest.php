<?php

namespace App\Http\Requests\Admin;

use App\Models\BusinessOperator;
use Illuminate\Foundation\Http\FormRequest;

class CreateBusinessRequest extends FormRequest
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
        $rules = [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    $emailHash = hash('sha256', $value);
                    $exists = BusinessOperator::where('email_hash', $emailHash)->exists();
                    if ($exists) {
                        $fail($attribute . 'は既に使用されています。');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'regex:/[a-z]/', // 1つの小文字を含む
                'regex:/[A-Z]/', // 1つの大文字を含む
                'regex:/[0-9]/', // 1つの数字を含む
            ],
            'corporationId' => 'nullable|integer|exists:corporations,corporation_id',
            'corporationName' => 'nullable|string|max:100',
            'businessName' => 'required|string|max:100',
            'zipCode' => 'required|numeric|regex:/^\d{7}$/',
            'prefCode' => 'required|string|size:2',
            'city' => 'required|string|max:200',
            'phone' => 'required|numeric|digits_between:8,11',
            'invoice' => 'nullable|numeric|regex:/^\d{13}$/',
            'businessForm' => 'nullable|string|max:50',
            'firstDeskNumber' => 'nullable|integer',
            'secondDeskNumber' => 'nullable|integer',
            'leadCompany' => 'nullable|integer|in:1,2,3',
            'notes' => 'nullable|string',
            'businessStatus' => 'required|integer|in:1,2,3',
            'businessDescription' => 'nullable|string|max:300',
            'isPublish' => 'required',
            'applicantName' => 'nullable|string|max:60',
            'applicantNameKana' => 'nullable|string|max:120|regex:/^[ァ-ヶー]+$/u',
        ];

        // 親法人が選択されていない場合、特定のフィールドを必須にする
        if (empty($this->corporationId)) {
            $rules['corporationName'] = 'required|string|max:100';
            $rules['applicantName'] = 'required|string|max:60';
            $rules['applicantNameKana'] = 'required|string|max:120|regex:/^[ァ-ヶー]+$/u';
            $rules['invoice'] = 'required|numeric|regex:/^\d{13}$/';
        }

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'corporationId' => '親法人ID',
            'corporationName' => '法人名',
            'businessName' => '事業者名',
            'zipCode' => '郵便番号',
            'prefCode' => '都道府県コード',
            'city' => '市区町村',
            'phone' => '電話番号',
            'invoice' => 'インボイス番号',
            'businessForm' => '事業形態',
            'firstDeskNumber' => '卓番号１',
            'secondDeskNumber' => '卓番号２',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'businessDescription' => '事業内容',
            'businessStatus' => '稼働状況',
            'isPublish' => '公開設定',
            'applicantName' => '申込者氏名',
            'applicantNameKana' => '申込者氏名（カナ）',
            'notes' => '備考',
            'leadCompany' => '営業元',
        ];
    }
}
