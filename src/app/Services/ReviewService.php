<?php

namespace App\Services;

use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;

class ReviewService
{
    private BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface;

    public function __construct(BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface)
    {
        $this->businessOperatorRepositoryInterface = $businessOperatorRepositoryInterface;
    }

    /**
     * 事業者：口コミ管理用データ取得
     * @param int $businessId
     */
    public function getBusinessOperatorReview(int $businessId)
    {
        $args = [];

        // 口コミデータ取得
        $reviews = $this->businessOperatorRepositoryInterface->getReviewBybusinessOrderByDate($businessId);

        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                $args[$review->date][] = [
                    'reviewId'  => $review->review_id,
                    'src'       => $review->user->userProfileImage->file_name,
                    'nickname'  => $review->user->nickname,
                    'comment'   => $review->comment,
                ];
            }
        }

        return $args;
    }

    /**
     * 口コミをid指定で削除
     * @param int $reviewId
     */
    public function deleteBusinessReview(int $reviewId)
    {
        $this->businessOperatorRepositoryInterface->deleteReviewById($reviewId);
    }
}
