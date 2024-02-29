<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\Point\PointRepositoryInterface;
use App\Repositories\PointFluctuationHistory\PointFluctuationHistoryRepositoryInterface;
use App\Trais\FileTrait;
use App\Enums\EntityType;
use App\Trais\StripeTrait;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;

class PointChargeService
{
    use FileTrait;
    use StripeTrait;

    protected $userRepository;
    protected $adminRepository;
    protected $pointRepository;
    protected $pointFluctuationHistoryRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AdminRepositoryInterface $adminRepository,
        PointRepositoryInterface $pointRepository,
        PointFluctuationHistoryRepositoryInterface $pointFluctuationHistoryRepository
    ) {
        $this->userRepository = $userRepository;
        $this->adminRepository = $adminRepository;
        $this->pointRepository = $pointRepository;
        $this->pointFluctuationHistoryRepository = $pointFluctuationHistoryRepository;
    }

    /**
     * ポイント購入一覧を取得
     *
     * @param int $freePoint 無償ポイント
     * @param int $paidPoint 有償ポイント
     * @param int $totalAmount 月の投げ銭合計金額
     * @param int $age ユーザーの年齢
     * @return array
     */
    public function getPointList(int $freePoint, int $paidPoint, int $totalAmount, int $age): array
    {
        $totalPoint = $freePoint + $paidPoint;
        $ageLimitPoint = $this->determineAvailablePoints($totalAmount, $age);
        $amount = $this->adminRepository->getAllTippingAmountSetting()->map(function ($item) {
            return [
                'settingId' => $item->setting_id,
                'amount' => $item->amount,
                'freeAmount' => $item->free_amount
            ];
        });

        $args = [
            'totalPoint' => $totalPoint,
            'ageLimitPoint' => $ageLimitPoint,
            'amounts' => $amount->toArray(),
        ];

        return $args;
    }

    /**
     * 年齢と月の投げ銭合計金額に基づいて使用可能なポイントを決定する
     * 投げ銭合計金額は使用可能ポイントから差し引かれる。
     *
     * @param int $totalAmount 月の投げ銭合計金額
     * @param int $age ユーザーの年齢
     * @return int 使用可能なポイント
     */
    private function determineAvailablePoints(int $totalAmount, int $age): int
    {
        if ($age < 15) {
            // maxメソッドを使用して負の値を返さないようにする
            return max(0, 5000 - $totalAmount);
        } elseif ($age <= 19) {
            return max(0, 20000 - $totalAmount);
        }

        return PHP_INT_MAX; // 上記以外の場合は無制限
    }
}
