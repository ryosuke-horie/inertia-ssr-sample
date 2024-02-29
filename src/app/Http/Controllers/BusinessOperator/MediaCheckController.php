<?php

namespace App\Http\Controllers\BusinessOperator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\MediaCheckService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class MediaCheckController extends Controller
{
    private MediaCheckService $mediaCheckService;

    public function __construct(MediaCheckService $mediaCheckService)
    {
        $this->mediaCheckService = $mediaCheckService;
    }

    /**
     * 初期表示
     * @return Response
     */
    public function index(): Response
    {
        $dateList = $this->mediaCheckService->list(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/MediaCheck/Index', [
            'businessName'  => Auth::guard('business-operator')->user()->business_name,
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
            return Redirect::route('business-operator.media-check.index')->with('success', '口コミを削除しました');
        } catch (\Exception $e) {
            return Redirect::route('business-operator.media-check.index')->with('success', '予期せぬエラーが発生しました');
        }
    }
}
