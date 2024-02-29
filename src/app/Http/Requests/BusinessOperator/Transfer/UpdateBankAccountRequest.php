<?php

namespace App\Http\Requests\BusinessOperator\Transfer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBankAccountRequest extends FormRequest
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
            'bankName'              => 'required|max:255',
            'accountType'           => 'required',
            'branchName'            => 'required|max:255',
            'accountNumber'         => 'required|numeric||digits:7',
            'accountHolderName'     => 'required|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'bankName'              => '銀行',
            'accountType'           => '銀行種別',
            'branchName'            => '支店名',
            'accountNumber'         => '口座番号',
            'accountHolderName'     => '口座名義',
        ];
    }
}
