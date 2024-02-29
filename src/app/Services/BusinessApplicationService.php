<?php

namespace App\Services;

use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use App\Repositories\Corporation\CorporationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Enums\ApplicationStatus;
use App\Trais\TokenTrait;
use App\Trais\EmailTrait;
use App\Enums\EntityType;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationBusinessMail;

class BusinessApplicationService
{
    use EmailTrait;

    private BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface;
    private CorporationRepositoryInterface $corporationRepositoryInterface;

    public function __construct(BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface, CorporationRepositoryInterface $corporationRepositoryInterface)
    {
        $this->businessOperatorRepositoryInterface = $businessOperatorRepositoryInterface;
        $this->corporationRepositoryInterface = $corporationRepositoryInterface;
    }

    /**
     * 店舗管理：一覧用データ取得
     * @param int $corporationId
     * @return Collection
     */
    public function management(int $corporationId): Collection
    {
        $businessApplications = $this->businessOperatorRepositoryInterface->getBusinessApplicationByCorporationId($corporationId);

        $newBusinessApplications = $businessApplications->map(function ($businessApplication) {
            if (
                is_null($businessApplication->businessOperator)
                || is_null($this->businessOperatorRepositoryInterface->findBusinessProfileImageByBusinessIdOrder($businessApplication->business_application_id, 1))
            ) {
                $src = 'kotei';
            } else {
                $src = $this->businessOperatorRepositoryInterface->findBusinessProfileImageByBusinessIdOrder($businessApplication->business_application_id, 1)->file_name;
            }

            return [
                'businessApplicationId' => $businessApplication->business_application_id,
                'businessName'          => is_null($businessApplication->businessOperator) ?
                                            $businessApplication->business_name :
                                            $businessApplication->businessOperator->business_name,
                'src'                   => $src,
                'applicationStatus'     => $businessApplication->application_status
            ];
        });

        return $newBusinessApplications;
    }

    /**
     * 店舗管理：詳細用データ取得
     * @param int $businessApplicationId
     *
     */
    public function managementshow(int $businessApplicationId)
    {
        return $this->businessOperatorRepositoryInterface->findBusinessApplicationByBusinessApplicationId($businessApplicationId);
    }

    /**
     * 関連店舗登録処理
     * @param int $corporationId
     * @param Request $request
     */
    public function createBusinessOperatorApplication(int $corporationId, Request $request)
    {
        $data = [
            'email'                 => $request->email,
            'corporation_id'        => $corporationId,
            'applicant_name'        => $request->applicantName,
            'applicant_name_kana'   => $request->applicantNameKana,
            'business_name'         => $request->businessName,
            'zip_code'              => $request->zipCode,
            'pref_code'             => $request->prefCode,
            'city'                  => $request->city,
            'phone'                 => $request->phone,
            'application_status'    => ApplicationStatus::Review
        ];

        $this->businessOperatorRepositoryInterface->createBusinessApplicationByRequest($data);
        $data['corporation_name'] = $this->corporationRepositoryInterface->findByCorporationId($corporationId)->corporation_name;

        Mail::to($request->email)->send(new ApplicationBusinessMail($data));
    }
}
