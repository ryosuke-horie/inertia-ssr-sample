<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\PasswordResetLinkRequest;
use App\Models\User;
use App\Trais\EmailTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    use EmailTrait;

    /**
     * パスワードリマインダー作成画面表示
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('User/Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * パスワードリマインダー作成
     * @param PasswordResetLinkRequest $request
     * @return Response
     * @throws ValidationException
     */
    public function store(PasswordResetLinkRequest $request): Response
    {
        // emailをハッシュ値に変換してリクエストに追加
        $emailHash = hash('sha256', $this->convertToLowercase($request->email));

        $status = Password::broker('users')->sendResetLink(['email_hash' => $emailHash]);

        if ($status == Password::RESET_LINK_SENT) {
            return Inertia::render('User/Auth/CompleteSendEmail');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
