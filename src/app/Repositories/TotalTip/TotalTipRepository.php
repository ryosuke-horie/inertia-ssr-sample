<?php

namespace App\Repositories\TotalTip;

use App\Enums\EntityType;
use App\Models\TotalTip;
use Illuminate\Support\Collection;

class TotalTipRepository implements TotalTipRepositoryInterface
{
    public function getYearMonthPointByBusiness(int $businessId): Collection
    {
        return TotalTip::selectRaw('total_tips.year_month, sum(total_tips.total_amount) as total_amount')
        ->join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->where('business_operators.business_id', $businessId)
        ->groupBy('total_tips.year_month')
        ->orderBy('total_tips.year_month', 'DESC')
        ->get();
    }

    /**
     * 累計投げ銭を月ごとに作成・更新する
     *
     * @param  EntityType $entityType
     * @param  int        $entityId
     * @param  string     $yearMonth
     * @return void
     */
    public function updateOrCreateTotalTip(EntityType $entityType, int $entityId, string $yearMonth, int $amount): void
    {
        // レコードを検索し、存在しなければ新規作成
        $totalTip = TotalTip::updateOrCreate(
            [
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'year_month'  => $yearMonth
            ],
            ['total_amount' => $amount]
        );

        // レコードが既に存在する場合は、total_amountを加算
        if ($totalTip->wasRecentlyCreated === false) {
            $totalTip->increment('total_amount', $amount);
        }
    }

    public function getPointByYearMonthBusiness(int $businessId, string $yearMonth): int
    {
        return TotalTip::join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->where('total_tips.year_month', $yearMonth)
        ->where('business_operators.business_id', $businessId)
        ->sum('total_tips.total_amount');
    }

    public function getStaffPointByYearMonthBusiness(int $businessId, string $yearMonth): Collection
    {
        return TotalTip::selectRaw('total_tips.entity_id, staff.staff_name, total_tips.total_amount')
        ->join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->where('total_tips.year_month', $yearMonth)
        ->where('business_operators.business_id', $businessId)
        ->groupBy('total_tips.entity_id', 'staff.staff_name', 'total_tips.total_amount')
        ->orderBy('total_tips.total_amount')
        ->get();
    }

    public function getStaffPointByBusinessPeriod(int $businessId, string $from, string $to): Collection
    {
        return TotalTip::selectRaw('total_tips.entity_id, staff.staff_name, sum(total_tips.total_amount) as total_amount')
        ->join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->whereBetween('total_tips.year_month', [$from, $to])
        ->where('business_operators.business_id', $businessId)
        ->groupBy('total_tips.entity_id', 'staff.staff_name')
        ->orderBy('total_amount', 'DESC')
        ->get();
    }

    public function getYearMonthPointByCorporation(int $corporationId): Collection
    {
        return TotalTip::selectRaw('total_tips.year_month, sum(total_tips.total_amount) as total_amount')
        ->join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->where('business_operators.corporation_id', $corporationId)
        ->groupBy('total_tips.year_month')
        ->orderBy('total_tips.year_month', 'DESC')
        ->get();
    }

    public function getPointByYearMonthCorporation(int $corporationId, string $yearMonth): int
    {
        return TotalTip::join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->where('total_tips.year_month', $yearMonth)
        ->where('business_operators.corporation_id', $corporationId)
        ->sum('total_tips.total_amount');
    }

    public function getBusinessPointByYearMonthCorporation(int $corporationId, string $yearMonth): Collection
    {
        return TotalTip::selectRaw('business_operators.business_id, business_operators.business_name, sum(total_tips.total_amount) as total_amount')
        ->join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->where('total_tips.year_month', $yearMonth)
        ->where('business_operators.corporation_id', $corporationId)
        ->groupBy('business_operators.business_id', 'business_operators.business_name')
        ->orderByRaw('sum(total_tips.total_amount) DESC')
        ->get();
    }

    /**
     * 投げ銭ユーザーランキングを累計で取得
     * @param string $yearMonth 年月 (例: 2024-01)
     * @return Collection
     * @details
     * - 50件まで取得
     * - total_amountの降順で取得
     * - user_id, nickname, total_amountを取得
     */
    public function getUsersRanking(string $yearMonth): Collection
    {
        return TotalTip::query()
            ->join('users', 'total_tips.entity_id', '=', 'users.user_id')
            ->where('total_tips.entity_type', 1)
            ->where('total_tips.year_month', $yearMonth)
            ->orderBy('total_tips.total_amount', 'desc')
            ->take(50)
            ->get(['total_tips.total_amount','users.user_id', 'users.nickname']);
    }

    /**
     * スタッフランキングを累計で取得
     * @param string $yearMonth 年月 (例: 2024-01)
     * @return Collection
     * @details
     * - 50件まで取得
     * - total_amountの降順で取得
     */
    public function getStaffRanking(string $yearMonth): Collection
    {
        return TotalTip::query()
            ->join('staff', 'total_tips.entity_id', '=', 'staff.staff_id')
            ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
            ->where('total_tips.entity_type', 2)
            ->where('total_tips.year_month', $yearMonth)
            ->orderBy('total_tips.total_amount', 'desc')
            ->take(50)
            ->get(['total_tips.total_amount','staff.staff_id', 'staff.staff_name', 'business_operators.business_id','business_operators.business_name']);
    }

    /**
     * ランキング管理の年月選択肢を取得
     * @param int $entityType 1:ユーザー, 2:スタッフ
     * @return Collection
     * @details
     * - entity_typeのレコードからyear_monthを取得
     * - 重複を除外
     * - 例: 2024-01, 2023-12, 2023-11
     * - ユーザーランキング、スタッフランキングで使用される想定
     */
    public function getYearMonthOptions(int $entityType): Collection
    {
        return TotalTip::select('year_month')
            ->where('entity_type', $entityType)
            ->groupBy('year_month')
            ->orderBy('year_month', 'desc')
            ->get();
    }

    public function getBusinessByCorporationIdPeriod(int $corporationId, string $from, string $to): Collection
    {
        return TotalTip::selectRaw('business_operators.business_id, business_operators.business_name, sum(total_tips.total_amount) as total_amount')
        ->join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->whereBetween('total_tips.year_month', [$from, $to])
        ->where('business_operators.corporation_id', $corporationId)
        ->groupBy('business_operators.business_id', 'business_operators.business_name')
        ->orderByRaw('sum(total_tips.total_amount) DESC')
        ->get();
    }

    public function getTotalAmountByBusinessPeriod(int $businessId, string $from, string $to): int
    {
        return TotalTip::join('staff', 'total_tips.entity_id', 'staff.staff_id')
        ->join('business_operators', 'staff.business_id', 'business_operators.business_id')
        ->where('total_tips.entity_type', EntityType::Staff)
        ->whereBetween('total_tips.year_month', [$from, $to])
        ->where('business_operators.business_id', $businessId)
        ->sum('total_tips.total_amount');
    }
}
