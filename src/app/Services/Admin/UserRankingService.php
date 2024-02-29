<?php

namespace App\Services\Admin;

use App\Repositories\TotalTip\TotalTipRepositoryInterface;
use Carbon\Carbon;

class UserRankingService
{
    protected $totalTipRepository;
    private int $entityType = 1; // ユーザーのentity_typeは1（total_tip）

    public function __construct(TotalTipRepositoryInterface $totalTipRepository)
    {
        $this->totalTipRepository = $totalTipRepository;
    }

    /**
     * @brief 投げ銭ユーザーランキングを取得
     * @param string|null $yearMonth
     * @return array
     */
    public function getUsersRanking(string|null $yearMonth)
    {
        // 年月が指定されていない場合は、現在の年月を取得（DBに合わせた形に成形）
        if (null === $yearMonth) {
            $yearMonth = Carbon::now()->format('Y-m');
        }

        $userRanking = $this->totalTipRepository->getUsersRanking($yearMonth);

        $userRanking = $userRanking->map(function ($item) {
            return [
                'userId' => $item->user_id,
                'nickname' => $item->nickname,
                'totalAmount' => $item->total_amount,
            ];
        });

        $args = [
            'userRanking' => $userRanking->toArray(),
        ];

        return $args;
    }

    /**
     * @brief 年月の選択肢を取得
     * @param string|null $yearMonth 年月 (例: 2024-01)
     * @return array
     * @details
     * - 返り値のyearMonthOptionsは選択中の年月が除外されます
     */
    public function getYearMonthOptions(?string $yearMonth = null)
    {
        // 年月が指定されていない場合は、現在の年月を取得
        if (null === $yearMonth) {
            $yearMonth = Carbon::now()->format('Y-m');
        }

        // ユーザーランキング管理の年月選択肢を取得
        $yearMonthOptions = $this->totalTipRepository->getYearMonthOptions($this->entityType);

        // 先にfilterメソッドを使用して、引数と同じyear_monthを持つ項目を除外
        $filteredOptions = $yearMonthOptions->filter(function ($item) use ($yearMonth) {
            return $item->year_month !== $yearMonth;
        });

        // その後、残った項目に対してmapメソッドを使用
        $args = $filteredOptions->map(function ($item) {
            return [
                'value' => $item->year_month,
                'label' => Carbon::parse($item->year_month)->format('Y年m月'),
            ];
        });

        return $args;
    }
}
