<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\StaffRankingService;
use Carbon\Carbon;
use Inertia\Inertia;

class StaffRankingController extends Controller
{
    private StaffRankingService $staffRankingService;

    public function __construct(StaffRankingService $staffRankingService)
    {
        $this->staffRankingService = $staffRankingService;
    }

    /**
     * スタッフランキング画面
     * @param string|null $yearMonth 年月 (例: 2024-01)
     * @return \Inertia\Response
     * @details
     * - 返り値のyearMonthはUIでのセレクトボックス表示用に使用
     * - 返り値のyearMonthOptionsはUIでのセレクトボックスの選択肢として使用
     *   - yearMonthOptionsは選択中の年月が除外されます
     */
    public function index(?string $yearMonth = null): \Inertia\Response
    {
        // 指定されている年月で上位50件のユーザーランキングを取得
        $rankingData = $this->staffRankingService->getStaffRanking($yearMonth);

        // 年月選択のセレクトボックス用に年月の選択肢を取得
        $yearMonthOptions = $this->staffRankingService->getYearMonthOptions($yearMonth);

        return Inertia::render('Admin/StaffRanking/Index', [
            ...$rankingData,
            'yearMonthOptions' => $yearMonthOptions,
            'yearMonth' => $yearMonth ? Carbon::parse($yearMonth)->format('Y年m月') : Carbon::now()->format('Y年m月'),
        ]);
    }
}
