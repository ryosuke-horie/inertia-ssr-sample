<?php

namespace App\Services;

use App\Enums\EntityType;
use App\Repositories\TotalTip\TotalTipRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\TransferRequest\TransferRequestRepositoryInterface;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use Carbon\Carbon;

class TotalPointService
{
    private StaffRepositoryInterface $staffRepositoryInterface;
    private TotalTipRepositoryInterface $totalTipRepositoryInterface;
    private TransferRequestRepositoryInterface $transferRequestRepositoryInterface;
    private BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface;

    public function __construct(
        StaffRepositoryInterface $staffRepositoryInterface,
        TotalTipRepositoryInterface $totalTipRepositoryInterface,
        TransferRequestRepositoryInterface $transferRequestRepositoryInterface,
        BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface
    ) {
        $this->staffRepositoryInterface = $staffRepositoryInterface;
        $this->totalTipRepositoryInterface = $totalTipRepositoryInterface;
        $this->transferRequestRepositoryInterface = $transferRequestRepositoryInterface;
        $this->businessOperatorRepositoryInterface = $businessOperatorRepositoryInterface;
    }

    /**
     * 事業者：ポイント集計一覧用データ取得
     * @param int $businessId
     * @return array
     */
    public function businessOperatorTotalPoint(int $businessId)
    {
        $args = [];

        // 未交換ポイント取得
        $args['totalPoint'] = $this->staffRepositoryInterface->getTotalPointByBusiness($businessId);

        // 年月毎のポイント取得
        $args['yearMonthList'] = $this->totalTipRepositoryInterface->getYearMonthPointByBusiness($businessId);

        // 交換済みかチェック
        $args['yearMonthList'] = $args['yearMonthList']->map(function ($yearMonth) use ($businessId) {
            return [
                'yearMonth'     => Carbon::parse($yearMonth->year_month)->format('Y年m月'),
                'totalAmount'  => number_format($yearMonth->total_amount),
                'isExchange'    => $this->transferRequestRepositoryInterface->getListByEntityDate(EntityType::BusinessOperator, $businessId, $yearMonth->year_month)

            ];
        });

        return $args;
    }

    /**
     * 事業者：スタッフ合計ポイント一覧用データ取得
     * @param int $businessId
     * @param string $yearMonth
     * @return array
     */
    public function businessOperatorStaffTotalPoint(int $businessId, string $yearMonth)
    {
        $args = [];

        // 合計ポイント取得
        $args['totalPoint'] = number_format($this->totalTipRepositoryInterface->getPointByYearMonthBusiness($businessId, $yearMonth));

        $args['isExchange'] = $this->transferRequestRepositoryInterface->getListByEntityDate(EntityType::BusinessOperator, $businessId, $yearMonth);

        // スタッフ毎の合計ポイント取得
        $staffList = $this->totalTipRepositoryInterface->getStaffPointByYearMonthBusiness($businessId, $yearMonth);


        $args['staffList'] = $staffList->map(function ($staff) {
            return [
                'staffName'     => $staff->staff_name,
                'totalAmount'   => number_format($staff->total_amount),
                'src'           => $this->staffRepositoryInterface->findStaffProfileImageByStaffIdOrder($staff->entity_id, 1)->file_name ?? ''
            ];
        });

        return $args;
    }

    /**
     * 法人：ポイント集計一覧用データ取得
     * @param int $corporationId
     * @return array
     */
    public function corporationTotalPoint(int $corporationId)
    {
        $args = [];

        // 未交換ポイント取得
        $args['totalPoint'] = $this->staffRepositoryInterface->getTotalPointByCorporation($corporationId);

        // 年月毎のポイント取得
        $args['yearMonthList'] = $this->totalTipRepositoryInterface->getYearMonthPointByCorporation($corporationId);

        // 交換済みかチェック
        $args['yearMonthList'] = $args['yearMonthList']->map(function ($yearMonth) use ($corporationId) {
            return [
                'yearMonth' => $yearMonth->year_month,
                'yearMonthDisplay'     => Carbon::parse($yearMonth->year_month)->format('Y年m月'),
                'totalAmount'  => number_format($yearMonth->total_amount),
                'isExchange'    => $this->transferRequestRepositoryInterface->getListByEntityDate(EntityType::Corporation, $corporationId, $yearMonth->year_month)

            ];
        });

        return $args;
    }

    /**
     * 法人：事業者合計ポイント一覧用データ取得
     * @param string $yearMonth
     * @return array
     */
    public function corporationBusinessOperatorTotalPoint(int $corporationId, string $yearMonth)
    {
        $args = [];

        // 合計ポイント取得
        $args['totalPoint'] = number_format($this->totalTipRepositoryInterface->getPointByYearMonthCorporation($corporationId, $yearMonth));

        $args['isExchange'] = $this->transferRequestRepositoryInterface->getListByEntityDate(EntityType::Corporation, $corporationId, $yearMonth);

        // 事業者毎の合計ポイント取得
        $businessOperators = $this->totalTipRepositoryInterface->getBusinessPointByYearMonthCorporation($corporationId, $yearMonth);

        $args['businessOperators'] = $businessOperators->map(function ($businessOperator) {
            return [
                'businessId'    => $businessOperator->business_id,
                'businessName'  => $businessOperator->business_name,
                'totalAmount'   => number_format($businessOperator->total_amount),
                'src'           => $this->businessOperatorRepositoryInterface->findBusinessProfileImageByBusinessIdOrder($businessOperator->business_id, 1)->file_name ?? ''
            ];
        });

        return $args;
    }
}
