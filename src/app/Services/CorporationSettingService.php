<?php

namespace App\Services;

use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use App\Repositories\Corporation\CorporationRepositoryInterface;
use Illuminate\Support\Collection;

class CorporationSettingService
{
    private BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface;

    public function __construct(BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface)
    {
        $this->businessOperatorRepositoryInterface = $businessOperatorRepositoryInterface;
    }

    /**
     * 公開設定用データ取得
     * @param int $corporationId
     * @return Collection $args
     */
    public function publish(int $corporationId): Collection
    {
        $businessOperators = $this->businessOperatorRepositoryInterface->getBusinessByCorporationId($corporationId);

        $args = $businessOperators->map(function ($businessOperator) {

            return [
                'settingId'     => $businessOperator->businessSettings->setting_id,
                'businessName'  => $businessOperator->business_name,
                'isPublish'     => $businessOperator->businessSettings->is_publish
            ];
        });

        return $args;
    }

    /**
     * 未公開の事業者が存在するか
     * @param int $corporationId
     * @return bool
     */
    public function isPublish(int $corporationId): bool
    {
        return $this->businessOperatorRepositoryInterface->existsPublishByCorporationId($corporationId);
    }
}
