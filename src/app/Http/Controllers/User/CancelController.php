<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\CancelService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class CancelController extends Controller
{
    private CancelService $cancelService;

    public function __construct(CancelService $cancelService)
    {
        $this->cancelService = $cancelService;
    }

    /**
     * 初期画面表示
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('User/Cancel/Index');
    }

    /**
     * 確認画面表示
     * @return Response
     */
    public function confirm(): Response
    {
        return Inertia::render('User/Cancel/Confirm');
    }

    /**
     * 削除処理
     * @return Response
     */
    public function delete(): Response | RedirectResponse
    {
        try {
            DB::transaction(function () {
                $this->cancelService->deleteUser(Auth::guard('user')->user());
            });
            Auth::guard('user')->logout();
            return Inertia::render('User/Cancel/Complete');
        } catch (\Exception $e) {
            return Redirect::route('user.cancel.index')->with('error', '予期せぬエラーが発生しました');
        }
    }
}
