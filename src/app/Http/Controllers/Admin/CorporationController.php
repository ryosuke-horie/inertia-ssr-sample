<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateBusinessRequest;
use App\Http\Requests\Admin\CreateCorporationRequest;
use App\Http\Requests\Admin\UpdateBusinessRequest;
use App\Http\Requests\Admin\UpdateCorporationRequest;
use App\Services\Admin\CorporationService;
use App\Services\CancelService;
use App\Trais\FlashMessageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class CorporationController extends Controller
{
    use FlashMessageTrait;

    private CorporationService $corporationService;
    // private CancelService $cancelService;

    public function __construct(CorporationService $corporationService)
    {
        $this->corporationService = $corporationService;
        // $this->cancelService = $cancelService;
    }

    /**
     * 法人一覧画面表示
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $args = !empty($request->except('page'))
            ? $this->corporationService->searchCorporations($request)
            : $this->corporationService->getAllCorporations();

        return Inertia::render('Admin/Corporation/Index', $args);
    }

    /**
     * 法人登録画面表示
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Corporation/Create');
    }

    /**
     * 法人登録
     *
     * @param CreateCorporationRequest $request
     * @return RedirectResponse
     */
    public function store(CreateCorporationRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->corporationService->createCorporation($request);
            });
            $this->flashMessageSuccess('登録が完了しました。');
            return Redirect::route('mits-admin.corporation.index');
        } catch (\Exception $e) {
            $this->flashMessageFailed('登録に失敗しました。エラー: ' . $e->getMessage());
            return Redirect::route('mits-admin.corporation.create');
        }
    }

    /**
     * 法人詳細画面表示
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $args = $this->corporationService->getCorporationById($request->corporationId);
        return Inertia::render('Admin/Corporation/Show', $args);
    }

    /**
     * 法人更新
     *
     * @param UpdateCorporationRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateCorporationRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $this->corporationService->updateCorporation($request);
            });
            $this->flashMessageSuccess('更新が完了しました。');
            return Redirect::route('mits-admin.corporation.show', ['corporationId' => $request->corporationId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed('更新に失敗しました。エラー: ' . $e->getMessage());
            return Redirect::route('mits-admin.corporation.show', ['corporationId' => $request->corporationId]);
        }
    }

    /**
     * 法人削除画面表示
     *
     * @param int $corporationId
     * @return Response
     */
    public function delete(int $corporationId): Response
    {
        return Inertia::render('Admin/Corporation/Delete', ['corporationId' => $corporationId,]);
    }

    /**
     * 法人削除
     * 削除処理は法人退会処理が完成次第、追加予定。。。
     */
    public function destroy(int $corporationId)
    {
        try {
            // DB::transaction(function () use ($corporationId) {
            //     $this->cancelService->deleteCorporation($corporationId);
            // });
            $this->flashMessageSuccess('削除が完了しました。');
            return Redirect::route('mits-admin.corporation.index');
        } catch (\Exception $e) {
            $this->flashMessageFailed('削除に失敗しました。エラー: ' . $e->getMessage());
            return Redirect::route('mits-admin.corporation.index');
        }
    }
}
