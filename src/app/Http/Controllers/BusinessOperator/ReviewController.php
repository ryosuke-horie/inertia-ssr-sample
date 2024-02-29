<?php

namespace App\Http\Controllers\BusinessOperator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Services\ReviewService;
use App\Http\Requests\BusinessOperator\Review\DeleteReviewRequest;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * 初期画面表示
     * @return Response
     */
    public function index(): Response
    {
        $args = $this->reviewService->getBusinessOperatorReview(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Review/Index', [
            'list' => $args
        ]);
    }

    /**
     * 削除処理
     * @param DeleteReviewRequest $request
     * @return RedirectResponse
     */
    public function delete(DeleteReviewRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $this->reviewService->deleteBusinessReview($request->reviewId);
            });
            return Redirect::route('business-operator.review.index')->with('success', '口コミを削除しました');
        } catch (\Exception $e) {
            return Redirect::route('business-operator.review.index')->with('success', '予期せぬエラーが発生しました');
        }
    }
}
