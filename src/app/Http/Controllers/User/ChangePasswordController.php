<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\User\UserChangePasswordRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(): Response
    {
        return Inertia::render('User/Profile/ChangePassword/Show');
    }

    public function update(UserChangePasswordRequest $request): RedirectResponse
    {
        $userId = Auth::guard('user')->user()->user_id;
        $password = $request->input('password');

        $this->userService->updatePassword($userId, $password);

        return Redirect::route('user.profile.show');
    }
}
