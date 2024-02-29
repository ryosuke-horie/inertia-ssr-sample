<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\UserService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewEmailController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function check(Request $request): RedirectResponse
    {
        $token = $request->input('token');
        $email = $request->input('email');
        try {
            $this->userService->updateEmail($token, $email);
            session(['previousName' => 'user.change-email.check']);
            return Redirect::route('user.change-email.complete');
        } catch (\Exception $e) {
            return Redirect::route('user.login');
        }
    }

    public function complete(Request $request): Response|RedirectResponse
    {
        // /user/change-emailからのリダイレクト以外はログインページに遷移
        $previousUrl = session('previousName');
        session()->forget('previousName');

        if ($previousUrl !== 'user.change-email.check') {
            return Redirect::route('user.login');
        }

        return Inertia::render('User/Auth/ChangeEmail/Complete');
    }
}
