<?php

namespace App\Services;

use App\Repositories\Point\PointRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UserPointHistoryService
{
    private UserRepositoryInterface $userRepository;
    private PointRepositoryInterface $pointRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PointRepositoryInterface $pointRepository
    ) {
        $this->userRepository = $userRepository;
        $this->pointRepository = $pointRepository;
    }

    public function getPoint(int $userId): array
    {
        $user = $this->userRepository->getUserProfileImageByUserId($userId);
        $now = Carbon::now();

        $pointHistories = $this->list($userId);
        $pointHistoryList = $pointHistories->take(3);
        $pointExpiredList = $pointHistories->filter(function ($point) use ($now) {
            if (Carbon::parse($point['expirationDate'])->lt($now)) {
                return false;
            }
            return $point["remainingPoints"] > 0;
        })->sortBy('expirationDate')->values()->take(3);

        $args = [
            'totalPoints' => $user->total_amount,
            'paidPoints' => $user->paid_points,
            'freePoints' => $user->free_points,
            'pointHistoryList' => $pointHistoryList,
            'pointExpiredList' => $pointExpiredList,
        ];

        return $args;
    }

    public function getPointHistoryList(int $userId): array
    {
        return [
            'pointHistoryList' => $this->list($userId)
        ];
    }

    public function getPointExpiredList(int $userId): array
    {
        $now = Carbon::now();
        $pointExpiredList = $this->list($userId)->filter(function ($point) use ($now) {
            if (Carbon::parse($point['expirationDate'])->lt($now)) {
                return false;
            }
            return $point["remainingPoints"] > 0 ;
        })->sortBy('expirationDate')->values();

        return [
            'pointExpiredList' => $pointExpiredList
        ];
    }

    private function list(int $userId): Collection
    {
        $points = $this->pointRepository->getAllPointByHistoryByUserId($userId);

        return $points->map(function ($point) {
            return [
                'buyId'           => $point->buy_id,
                'buyPoints'       => $point->buy_points,
                'remainingPoints' => $point->remaining_points,
                'isPaid'          => $point->is_paid,
                'createdAt'       => Carbon::parse($point->created_at)->format('Y/m/d'),
                'expirationDate'  => Carbon::parse($point->expiration_date)->format('Y/m/d'),
            ];
        });
    }
}
