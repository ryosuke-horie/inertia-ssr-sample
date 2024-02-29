<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\Auth\PasswordResetLinkRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * パスワードリマインダー作成画面表示
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Staff/Auth/ForgotPassword', [
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
        $email = $request->email;
        $emailHash = hash('sha256', $email);

        $status = Password::broker('staff')->sendResetLink(['email_hash' => $emailHash]);

        if ($status == Password::RESET_LINK_SENT) {
            return Inertia::render('Staff/Auth/CompleteForgotPasswordSendEmail');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
