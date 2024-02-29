<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\MediaCheckService;
use App\Services\BusinessOperatorService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class MediaCheckController extends Controller
{
    use FlashMessageTrait;

    private MediaCheckService $mediaCheckService;
    private BusinessOperatorService $businessOperatorService;

    public function __construct(MediaCheckService $mediaCheckService, BusinessOperatorService $businessOperatorService)
    {
        $this->mediaCheckService = $mediaCheckService;
        $this->businessOperatorService = $businessOperatorService;
    }

    /**
     * 初期表示
     * @return Response
     */
    public function index(): Response
    {
        $businessOperators = $this->mediaCheckService->getBusinessOperators(Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/MediaCheck/Index', [
            'businessOperators'  => $businessOperators,
        ]);
    }

    /**
     * 事業者メディア詳細
     * @param int $businessId
     * @return Response
     */
    public function show(int $businessId): Response
    {
        $dateList = $this->mediaCheckService->list($businessId);
        $businessOperator = $this->businessOperatorService->findByBusinessId($businessId);

        return Inertia::render('Corporation/MediaCheck/Show', [
            'businessId'    => $businessOperator->business_id,
            'businessName'  => $businessOperator->business_name,
            'dateList'      => $dateList
        ]);
    }

    /**
     * 削除処理
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $this->mediaCheckService->deleteStaffMedia($request->mediaId);
            });
            $this->flashMessageSuccess('画像を削除しました');
            return Redirect::route('corporation.media-check.show', ['businessId' => $request->businessId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed('予期せぬエラーが発生しました');
            return Redirect::route('corporation.media-check.show', ['businessId' => $request->businessId]);
        }
    }
}
