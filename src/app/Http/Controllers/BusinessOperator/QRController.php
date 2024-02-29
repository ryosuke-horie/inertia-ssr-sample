<?php

namespace App\Http\Controllers\BusinessOperator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Enums\EntityType;
use Illuminate\Support\Facades\Redirect;
use App\Services\QRService;
use App\Services\BusinessOperatorService;
use App\Services\StaffService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class QRController extends Controller
{
    private QRService $QRService;
    private BusinessOperatorService $businessOperatorService;
    private StaffService $staffService;

    public function __construct(QRService $QRService, BusinessOperatorService $businessOperatorService, StaffService $staffService)
    {
        $this->QRService = $QRService;
        $this->businessOperatorService = $businessOperatorService;
        $this->staffService = $staffService;
    }

    /**
     * 初期表示
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('BusinessOperator/QR/Index');
    }

    /**
     * ショップQRコード画面表示
     *
     * @return Response
     */
    public function businessOperator(): Response
    {

        $args = $this->businessOperatorService->findByBusinessId(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/QR/BusinessOperator', [
            'firstDeskNumber'   => $args->first_desk_number,
            'secondDeskNumber'  => $args->second_desk_number
        ]);
    }

    /**
     * スタッフQRコード画面表示
     *
     * @return Response
     */
    public function staff(): Response
    {
        $staffList = $this->staffService->getStaffByBusinessId(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/QR/Staff', [
            'staffList' => $staffList
        ]);
    }

    /**
     * API：スタッフQRコード生成
     *
     * @return JsonResponse
     */
    public function createStaff(Request $request): JsonResponse
    {
        $args = $this->QRService->createStaff($request->staffIdList, $request->size);

        return response()->json([
            'filePath'  => $args['filePath'],
            'fileName'  => $args['fileName']
        ]);
    }

    /**
     * API：ショップQRコード生成
     *
     * @return JsonResponse
     */
    public function createBusinessOperator(Request $request): JsonResponse
    {

        $businessId = Auth::guard('business-operator')->user()->business_id;

        $args = $this->QRService->createBusinessOperator($businessId, $request->isMulti, $request->isAbroad, $request->size, $request->firstDeskNumber, $request->secondDeskNumber);

        return response()->json([
            'filePath'  => $args['filePath'],
            'fileName'  => $args['fileName']
        ]);
    }

    /**
     * ファイル削除
     *
     * @return JsonResponse
     */
    public function deleteFile(Request $request): JsonResponse
    {
        $response = $this->QRService->deleteFile(str_replace(config('app.url') . '/storage/', 'public/', $request->filePath));

        return response()->json([
            'response'  => $response
        ]);
    }
}
