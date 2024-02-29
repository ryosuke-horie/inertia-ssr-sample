<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\ProfileUpdateRequest;
use App\Models\StaffNotification;
use App\Services\StaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    use FlashMessageTrait;

    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * スタッフプロフィール画面表示
     * @return Response
     */
    public function show(): Response
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $args = $this->staffService->getProfile($staffId);
        return Inertia::render('Staff/Profile/Show', $args);
    }

    /**
     * スタッフプロフィール更新
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $staffId = Auth::guard('staff')->user()->staff_id;
                $this->staffService->updateProfile($staffId, $request);
            });
            $this->flashMessageSuccess('プロフィールを更新しました');
            return Redirect::route('staff.profile.show');
        } catch (\Exception $e) {
            return Redirect::route('staff.profile.show');
        }
    }

    /**
     * プロフィール各種設定画面表示
     * @return Response
     */
    public function showSetting(): Response
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $args = $this->staffService->getSetting($staffId);
        return Inertia::render('Staff/Profile/ShowSetting', $args);
    }

    public function updateProfileImage(Request $request)
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $args = $this->staffService->updateProfileImage($staffId, $request);
        return response()->json($args);
    }

    public function deleteProfileImage(Request $request)
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $args = $this->staffService->deleteProfileImage($staffId, $request);
        return response()->json($args);
    }

    public function updateSettingMessageNotified(Request $request)
    {
        $isMessageNotified = $request->input('isMessageNotified');

        $staffId = Auth::guard('staff')->user()->staff_id;
        $staffNotification = StaffNotification::where('staff_id', $staffId)->first();
        $staffNotification->is_message_notified = $isMessageNotified;
        $staffNotification->save();

        return response()->json(['response' => true]);
    }
}
