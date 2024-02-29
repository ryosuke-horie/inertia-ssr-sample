<?php

namespace App\Services;

use App\Repositories\StaffReply\StaffReplyRepositoryInterface;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use Illuminate\Support\Collection;

class MediaCheckService
{
    private StaffReplyRepositoryInterface $staffReplyRepositoryInterface;
    private BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface;

    public function __construct(StaffReplyRepositoryInterface $staffReplyRepositoryInterface, BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface)
    {
        $this->staffReplyRepositoryInterface = $staffReplyRepositoryInterface;
        $this->businessOperatorRepositoryInterface = $businessOperatorRepositoryInterface;
    }

    /**
     * 投稿動画・画像確認画面用データ取得
     * @param int $businessId
     * @return array
     */
    public function list(int $businessId): array
    {
        $args = [];

        $list = $this->staffReplyRepositoryInterface->getReplyMediaByBusiness($businessId);

        $index = -1;
        $date = '';
        foreach ($list as $data) {
            if ($date != $data->date) {
                $date = $data->date;
                $index++;
            }
            $args[$index]['date'] = $data->date;
            $args[$index]['staffList'][] = [
                'tipId'         => $data->tip_id,
                'mediaId'       => $data->media_id,
                'staffName'     => $data->staff_name,
                'staffFileName' => $data->staff_file_name,
                'userName'      => $data->user_name,
                'replyFileName' => $data->reply_file_name,
                'replyFileType' => $data->reply_file_type
            ];
        }

        return $args;
    }

    /**
     * 投稿動画・画像の削除
     * @param int $mediaId
     */
    public function deleteStaffMedia(int $mediaId)
    {
        $this->staffReplyRepositoryInterface->deleteStaffMedia($mediaId);
    }

    /**
     * 法人：事業者選択画面用データ
     * @param int $corporationId
     */
    public function getBusinessOperators(int $corporationId)
    {
        $businessOperators = $this->businessOperatorRepositoryInterface->getBusinessByCorporationId($corporationId);

        $args = $businessOperators->map(function ($businessOperator) {
            return [
                'businessId'    => $businessOperator->business_id,
                'businessName'  => $businessOperator->business_name,
                'src'           => $this->businessOperatorRepositoryInterface->findBusinessProfileImageByBusinessIdOrder($businessOperator->business_id, 1)->file_name ?? ''
            ];
        });

        return $args;
    }
}
