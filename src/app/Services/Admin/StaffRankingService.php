<?php

namespace App\Services\Admin;

use App\Repositories\TotalTip\TotalTipRepositoryInterface;
use Carbon\Carbon;

class StaffRankingService
{
    protected $totalTipRepository;
    private int $entityType = 2; // スタッフのentity_typeは2（total_tip）

    public function __construct(TotalTipRepositoryInterface $totalTipRepository)
    {
        $this->totalTipRepository = $totalTipRepository;
    }

    /**
     * @brief スタッフランキングを取得
     * @param string|null $yearMonth
     * @return array
     */
    public function getStaffRanking(string|null $yearMonth)
    {
        // 年月が指定されていない場合は、現在の年月を取得（DBに合わせた形に成形）
        if (null === $yearMonth) {
            $yearMonth = Carbon::now()->format('Y-m');
        }

        $staffRanking = $this->totalTipRepository->getStaffRanking($yearMonth);

        $staffRanking = $staffRanking->map(function ($item) {
            return [
                'totalAmount' => $item->total_amount,
                'staffId' => $item->staff_id,
                'staffName' => $item->staff_name,
                'businessId' => $item->business_id,
                'businessName' => $item->business_name,
            ];
        });

        $args = [
            'staffRanking' => $staffRanking->toArray(),
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

        // entity_type=2（スタッフ）の選択可能な年月を取得
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
