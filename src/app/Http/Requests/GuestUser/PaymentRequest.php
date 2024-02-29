<?php

namespace App\Http\Requests\GuestUser;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'message' => ['nullable', 'string', 'max:200'],
            'staffId' => ['required', 'integer'],
            'staffName' => ['required', 'string'],
            'businessId' => ['required', 'integer'],
            'guestNickname' => ['required', 'string', 'max:50'],
        ];
    }
}
