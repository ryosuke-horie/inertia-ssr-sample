<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\User\UserChangeEmailCreateTokenRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ChangeEmailController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(): Response
    {
        return Inertia::render('User/Profile/ChangeEmail/Show');
    }

    public function create(UserChangeEmailCreateTokenRequest $request): RedirectResponse
    {
        $user = Auth::guard('user')->user();
        $email = $request->input('email');

        $this->userService->registerTokenForEmail($user->user_id, $user->nickname, $email);

        return Redirect::route('user.profile.show');
    }
}
