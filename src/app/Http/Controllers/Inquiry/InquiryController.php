<?php

namespace App\Http\Controllers\Inquiry;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inquiry\InquiryRequest;
use App\Services\InquiryService;
use App\Trais\FlashMessageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class InquiryController extends Controller
{
    use FlashMessageTrait;

    private InquiryService $inquiryService;

    public function __construct(InquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }

    /**
     * 初期画面表示
     * @return Response
     */
    public function index(Request $request): Response
    {
        $path = $this->inquiryService->convertPathToUpperCamelCase($request);
        return Inertia::render($path . '/Index');
    }

    /**
     * 確認画面表示
     * @return Response
     */
    public function confirm(InquiryRequest $request): Response
    {
        $path = $this->inquiryService->convertPathToUpperCamelCase($request);
        return Inertia::render($path, $request->all());
    }

    /**
     * 登録処理
     * @return Response
     */
    public function complete(InquiryRequest $request): Response | RedirectResponse
    {
        $path = $this->inquiryService->convertPathToUpperCamelCase($request);
        // entityType と userId を取得
        ['entityType' => $entityType, 'userId' => $userId] = $this->inquiryService->getEntityTypeAndUserId($path);
        try {
            DB::transaction(function () use ($request, $entityType, $userId) {
                $this->inquiryService->storeInquiry($request->name, $request->email, $request->content, $entityType, $userId);
            });
            return Inertia::render($path);
        } catch (\Exception $e) {
            $this->flashMessageFailed('お問い合わせの登録に失敗しました。エラー: ' . $e->getMessage());
            return Redirect::route('inquiry.index');
        }
    }
}
