<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateBusinessRequest;
use App\Http\Requests\Admin\UpdateBusinessRequest;
use App\Models\BusinessOperator;
use App\Services\Admin\BusinessOperatorService;
use App\Services\CancelService;
use App\Trais\FlashMessageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class BusinessOperatorController extends Controller
{
    use FlashMessageTrait;

    private BusinessOperatorService $businessOperatorService;
    private CancelService $cancelService;

    public function __construct(BusinessOperatorService $businessOperatorService, CancelService $cancelService)
    {
        $this->businessOperatorService = $businessOperatorService;
        $this->cancelService = $cancelService;
    }

    /**
     * 事業者一覧画面表示
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $args = !empty($request->except('page'))
            ? $this->businessOperatorService->searchBusiness($request)
            : $this->businessOperatorService->getAllBusiness();

        return Inertia::render('Admin/BusinessOperator/Index', $args);
    }

    /**
     * 事業者登録画面表示
     *
     * @return Response
     */
    public function create(): Response
    {
        $args = $this->businessOperatorService->prepareBusinessCreation();
        return Inertia::render('Admin/BusinessOperator/Create', $args);
    }

    /**
     * 事業者登録
     */
    public function store(CreateBusinessRequest $request)
    {

        try {
            DB::transaction(function () use ($request) {
                $this->businessOperatorService->createBusiness($request);
            });
            $this->flashMessageSuccess('登録が完了しました。');
            return Redirect::route('mits-admin.business-operator.index');
        } catch (\Exception $e) {
            $this->flashMessageFailed('登録に失敗しました。エラー: ' . $e->getMessage());
            return Redirect::route('mits-admin.business-operator.create');
        }
    }

    /**
     * 事業者詳細画面表示
     *
     * @return Response
     */
    public function show(Request $request): Response
    {
        $args = $this->businessOperatorService->getBusinessById($request->businessId);
        return Inertia::render('Admin/BusinessOperator/Show', $args);
    }

    /**
     * 事業者更新
     */
    public function update(UpdateBusinessRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->businessOperatorService->updateBusiness($request);
            });
            $this->flashMessageSuccess('更新が完了しました。');
            return Redirect::route('mits-admin.business-operator.show', ['businessId' => $request->businessId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed('更新に失敗しました。エラー: ' . $e->getMessage());
            return Redirect::route('mits-admin.business-operator.show', ['businessId' => $request->businessId]);
        }
    }

    /**
     * 事業者削除画面表示
     *
     * @return Response
     */
    public function delete(int $businessId): Response
    {
        return Inertia::render('Admin/BusinessOperator/Delete', ['businessId' => $businessId,]);
    }

    /**
     * 事業者削除
     */
    public function destroy(int $businessId)
    {
        try {
            DB::transaction(function () use ($businessId) {
                $this->cancelService->deleteBusinessOperator($businessId);
            });
            $this->flashMessageSuccess('削除が完了しました。');
            return Redirect::route('mits-admin.business-operator.index');
        } catch (\Exception $e) {
            $this->flashMessageFailed('削除に失敗しました。エラー: ' . $e->getMessage());
            return Redirect::route('mits-admin.business-operator.index');
        }
    }

    /**
     * 対象事業者口コミ一覧画面表示
     *
     * @return Response
     */
    public function reviewIndex(int $businessId): Response
    {
        $reviews = $this->businessOperatorService->getAllBsuinessReviews($businessId);
        return Inertia::render('Admin/BusinessOperator/Review/Index', [
            'commentList' => $reviews,
        ]);
    }

    /**
     * 対象事業者口コミ詳細
     *
     * @return Response
     */
    public function reviewShow(int $reviewId): Response
    {
        $review = $this->businessOperatorService->getReview($reviewId);
        return Inertia::render('Admin/BusinessOperator/Review/Show', [
            'review' => $review,
        ]);
    }

    /**
     * 事業者口コミ削除
     *
     * @return RedirectResponse
     */
    public function reviewDestroy(int $reviewId): RedirectResponse
    {
        $this->businessOperatorService->deleteReviewById($reviewId);
        return to_route('admin.business-operator.review.index');
    }
}
