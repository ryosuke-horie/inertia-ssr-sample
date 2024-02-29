<?php

declare(strict_types=1);

namespace App\Http\Controllers\AdminStaff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class MypageController extends Controller
{
    /**
     * 管理者スタッフマイページ画面表示
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('AdminStaff/Mypage/Mypage');
    }
}
