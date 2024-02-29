<?php

namespace App\Repositories\TotalTip;

use App\Enums\EntityType;
use Illuminate\Support\Collection;

interface TotalTipRepositoryInterface
{
    /**
     * 対象事業者の年月毎の合計ポイントを取得
     * @param int $businessId
     * @return Collection
     */
    public function getYearMonthPointByBusiness(int $businessId): Collection;

    /**
     * 累計投げ銭を月ごとに作成・更新する
     *
     * @param EntityType $entityType
     * @param int $entityId
     * @param string $yearMonth
     * @return void
     */
    public function updateOrCreateTotalTip(EntityType $entityType, int $entityId, string $yearMonth, int $amont): void;

    /**
     * 対象事業者・年月の合計ポイントを取得
     * @param int $businessId
     * @param string $yearMonth
     * @return int
     */
    public function getPointByYearMonthBusiness(int $businessId, string $yearMonth): int;

    /**
     * 対象事業者・期間のスタッフ毎の合計ポイントを取得
     * @param int $businessId
     * @param string $yearMonth
     * @return Collection
     */
    public function getStaffPointByYearMonthBusiness(int $businessId, string $yearMonth): Collection;

    /**
     * 対象事業者・期間(範囲)のスタッフ毎の合計ポイントを取得
     * @param int $businessId
     * @param string $from
     * @param string $to
     * @return Collection
     */
    public function getStaffPointByBusinessPeriod(int $businessId, string $from, string $to): Collection;

    /**
     * 対象法人の年月毎の合計ポイントを取得
     * @param int $corporationId
     * @return Collection
     */
    public function getYearMonthPointByCorporation(int $corporationId): Collection;

    /**
     * 対象法人・年月の合計ポイントを取得
     * @param int $corporationId
     * @param string $yearMonth
     * @return int
     */
    public function getPointByYearMonthCorporation(int $corporationId, string $yearMonth): int;

    /**
     * 対象法人・期間の事業者毎の合計ポイントを取得
     * @param int $corporationId
     * @param string $yearMonth
     * @return Collection
     */
    public function getBusinessPointByYearMonthCorporation(int $corporationId, string $yearMonth): Collection;

    /**
     * 投げ銭ユーザーランキングを累計で取得
     * @param string $yearMonth
     * @return Collection
     */
    public function getUsersRanking(string $yearMonth): Collection;

    /**
     * 投げ銭ユーザーランキングの年月選択肢を取得
     * @param int $entityType
     * @return Collection
     */
    public function getYearMonthOptions(int $entityType): Collection;

    /**
     * スタッフランキングを累計で取得
     * @param string $yearMonth
     * @return Collection
     */
    public function getStaffRanking(string $yearMonth): Collection;

    /**
     * 事業者毎の売上を取得
     * @param int $corporationId
     * @param string $from
     * @param string $to
     * @return Collection
     */
    public function getBusinessByCorporationIdPeriod(int $corporationId, string $from, string $to): Collection;

    /**
     * 事業者の売上を取得
     * @param int $businessId
     * @param string $from
     * @param string $to
     * @return int
     */
    public function getTotalAmountByBusinessPeriod(int $businessId, string $from, string $to): int;
}
