<?php

namespace App\Repositories\Point;

use App\Models\PointBuyHistory;
use App\Models\PointUsageHistory;
use Illuminate\Support\Collection;

class PointRepository implements PointRepositoryInterface
{
    public function getAllPointByHistoryByUserId(int $userId): Collection
    {
        return PointBuyHistory::where('user_id', $userId)->orderByDesc('created_at')->get();
    }

    public function getUsablePointBuyHistoryByUserId(int $userId): Collection
    {
        $where = ['user_id' => $userId];
        return PointBuyHistory::where($where)->orderBy('created_at', 'asc')->get();
    }

    public function updateRemainingPoints(int $buyId, int $remainingPoints, bool $isPointUsable): void
    {
        PointBuyHistory::where('buy_id', $buyId)->update([
            'remaining_points' => $remainingPoints,
        ]);
    }

    public function createPointUsageHistory(int $buyId, int $userTipId, int $usedPoints): void
    {
        PointUsageHistory::create([
            'buy_id' => $buyId,
            'tip_id' => $userTipId,
            'used_points' => $usedPoints,
        ]);
    }

    public function createPointBuyHistory(int $userId, int $buyAmount, string $paymentIntentId, bool $isPaid, string $expirationDate): void
    {
        PointBuyHistory::create([
            'user_id'           => $userId,
            'buy_points'        => $buyAmount,
            'amount'            => $buyAmount,
            'remaining_points'  => $buyAmount,
            'payment_intent_id' => $paymentIntentId,
            'is_paid'           => $isPaid,
            'expiration_date'   => $expirationDate, // 6か月後
        ]);
    }
}
