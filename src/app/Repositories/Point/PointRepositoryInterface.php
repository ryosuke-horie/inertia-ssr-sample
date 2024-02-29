<?php

namespace App\Repositories\Point;

use Illuminate\Support\Collection;

interface PointRepositoryInterface
{
    /**
     * ポイント獲得履歴一覧取得
     */
    public function getAllPointByHistoryByUserId(int $userId): Collection;

    /**
     * 利用可能なすべてのポイント詳細を取得
     *
     * @param int $userId ユーザーID
     * @return Collection
     */
    public function getUsablePointBuyHistoryByUserId(int $userId): Collection;

    /**
     * ポイント詳細を更新
     *
     * @param int $buyId ポイント購入履歴ID
     * @param int $remainingPoints 残ポイント
     * @param bool $isPointUsable ポイント利用可能フラグ
     * @return void
     */
    public function updateRemainingPoints(int $buyId, int $remainingPoints, bool $isPointUsable): void;

    /**
     * ポイント利用履歴を作成
     *
     * @param int $detailId ポイント詳細ID
     * @param int $userTipId 投げ銭ID
     * @param int $usedPoints 利用ポイント
     * @return void
     */
    public function createPointUsageHistory(int $detailId, int $userTipId, int $usedPoints): void;

    /**
     * ポイント購入履歴を作成
     *
     * @param int $userId
     * @param int $buyAmount
     * @param string $paymentIntentId
     * @param bool $isPaid
     * @param string $expirationDate
     * @return void
     */
    public function createPointBuyHistory(int $userId, int $buyAmount, string $paymentIntentId, bool $isPaid, string $expirationDate): void;
}
