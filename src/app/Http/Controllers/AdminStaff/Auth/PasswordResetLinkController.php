<?php

declare(strict_types=1);

namespace App\Http\Controllers\AdminStaff\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminStaff\Auth\PasswordResetLinkRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * 管理者スタッフパスワードリセット表示
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('AdminStaff/Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * 管理者スタッフパスワードリセットトークン＆メール送信
     * @param PasswordResetLinkRequest $request
     * @return Response
     * @throws ValidationException
     */
    public function store(PasswordResetLinkRequest $request): Response
    {
        $email = $request->email;
        $emailHash = hash('sha256', $email);

        $status = Password::broker('admin-staff')->sendResetLink(['email_hash' => $emailHash]);

        if ($status == Password::RESET_LINK_SENT) {
            return Inertia::render('AdminStaff/Auth/CompleteForgotPasswordSendEmail');
        }

        throw ValidationException::withMessages(['email' => [trans($status)]]);
    }
}
