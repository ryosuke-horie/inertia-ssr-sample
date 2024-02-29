<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(): Response
    {
        $userId = Auth::guard('user')->user()->user_id;
        $args = $this->userService->getUserProfileImage($userId);
        return Inertia::render('User/Profile/Show', $args);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $userId = Auth::guard('user')->user()->user_id;
            $this->userService->updateUserProfile($userId, $request->nickname);
        } catch (\Exception $e) {
            return Redirect::route('user.profile.show')->with('fail', 'プロフィール更新に失敗しました。');
        }
        return Redirect::route('user.profile.show');
    }

    public function updateUserProfileImage(Request $request)
    {
        $userId = Auth::guard('user')->user()->user_id;
        $args = $this->userService->updateUserProfileImage($userId, $request);
        return response()->json(['userProfileImage' => $args['userProfileImage']]);
    }

    /**
     * ランキング設定画面表示
     * @return Response
     */
    public function showSetting(): Response
    {
        $userId = Auth::guard('user')->user()->user_id;
        $args = $this->userService->getUserProfileImage($userId);
        return Inertia::render('User/Profile/Setting/Show', [
            'isShowRanking' => $args['isShowRanking'],
        ]);
    }

    /**
     * API：ランキング表示非表示更新
     */
    public function updateSettingShowRanking(Request $request)
    {
        $isShowRanking = $request->input('isShowRanking');

        $userId = Auth::guard('user')->user()->user_id;
        $user = User::find($userId);
        $user->is_show_ranking = $isShowRanking;
        $user->save();

        return response()->json(['response' => true]);
    }
}
