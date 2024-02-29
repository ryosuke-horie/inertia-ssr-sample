<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\NewPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    /**
     * パスワードリセット画面表示
     * @return Response
     */
    public function create(Request $request): Response
    {
        return Inertia::render('User/Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * パスワードリセット更新
     * @param NewPasswordRequest $request
     * @return Response
     * @throws ValidationException
     */
    public function store(NewPasswordRequest $request): Response
    {
        $email = $request->email;
        $emailHash = hash('sha256', $email);

        $reset = [
            'email_hash'            => $emailHash,
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'token'                 => $request->token,
        ];

        $status = Password::broker('users')->reset(
            $reset,
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return Inertia::render('User/Auth/CompleteResetPassword', []);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
