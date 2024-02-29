<?php

namespace App\Http\Requests\User\Auth;

use App\Models\User;
use App\Trais\EmailTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    use EmailTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // birthdate を生成してリクエストデータにマージ
        $birthdate = Carbon::createFromDate($this->year, $this->month, $this->day)->toDateString();
        $this->merge(['birthdate' => $birthdate]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nickname' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . now()->year,
            'month' => 'required|integer|min:1|max:12',
            'day' => 'required|integer|min:1|max:31',
            'birthdate' => 'required|date|before_or_equal:today',
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->email;
            // emailがnullの場合は処理をスキップ
            if (is_null($email)) {
                return;
            }

            $emailLowerHash = hash('sha256', $this->convertToLowercase($email));
            if (User::where('email_hash', $emailLowerHash)->exists()) {
                $validator->errors()->add('email', '指定されたメールアドレスはすでに存在します。');
            }
        });
    }
}
