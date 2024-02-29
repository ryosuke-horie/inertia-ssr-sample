<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffNotification;
use App\Services\StaffService;

class ApiStaffController extends Controller
{
    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * API：スタッフ一覧取得
     */
    public function index()
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $staffList = $this->staffService->getStaffByBusinessId($businessId);
        return response()->json(['staffList' => $staffList]);
    }

    /**
     * API:スタッフ論理削除
     * @param int $staffId
     */
    public function delete(int $staffId): void
    {
        $this->staffService->deleteStaff($staffId);
    }

    /**
     * API：スタッフ画像更新
     */
    public function updateProfileImage(int $staffId, Request $request)
    {
        $args = $this->staffService->updateProfileImage($staffId, $request);
        return response()->json($args);
    }

    /**
     * API：スタッフ画像削除
     */
    public function deleteProfileImage(int $staffId, Request $request)
    {
        $args = $this->staffService->deleteProfileImage($staffId, $request);
        return response()->json($args);
    }

    /**
     * API：スタッフ各種設定更新
     */
    public function updateSettingMessageNotified(int $staffId, Request $request)
    {
        $isMessageNotified = $request->input('isMessageNotified');

        $staffNotification = StaffNotification::where('staff_id', $staffId)->first();
        $staffNotification->is_message_notified = $isMessageNotified;
        $staffNotification->save();

        return response()->json(['response' => true]);
    }

    /**
     * API：スタッフスケジュール取得
     */
    public function getStaffSchedules(int $staffId)
    {
        $args = $this->staffService->getStaffSchedules($staffId);
        return response()->json($args);
    }

    /**
     * API：スタッフスケジュール更新
     */
    public function updateStaffSchedules(int $staffId, Request $request)
    {
        $schedules = $request->input('schedules');
        $this->staffService->updateStaffSchedules($staffId, $schedules);
        return response()->json($this->staffService->getStaffSchedules($staffId));
    }
}
