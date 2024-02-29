<?php

namespace App\Services\Admin;

use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use App\Repositories\Corporation\CorporationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BusinessOperatorService
{
    private BusinessOperatorRepositoryInterface $businessOperatorRepository;
    private CorporationRepositoryInterface $corporationRepository;

    public function __construct(BusinessOperatorRepositoryInterface $businessOperatorRepository, CorporationRepositoryInterface $corporationRepository)
    {
        $this->businessOperatorRepository = $businessOperatorRepository;
        $this->corporationRepository = $corporationRepository;
    }

    /**
     * 事業者一覧取得
     *
     * @return array
     */
    public function getAllBusiness(): array
    {
        $business = $this->businessOperatorRepository->getAllPaginateBusiness();

        $args = [
            'businessList'  => $this->formatBusinessOperatorsData($business),
            'businessCount' => $business->total(),
            'parentCorporationList' => $this->formatCorporationsData($this->corporationRepository->getAllCorporations()),
            'pagination'    => $this->formatPaginationData($business),
        ];

        return $args;
    }

    /**
     * 事業者一覧取得（検索）
     *
     * @return array
     */
    public function searchBusiness(Request $request): array
    {
        $business = $this->businessOperatorRepository->searchBusinessOperatorByRequest($request);

        $args = [
            'businessList'  => $this->formatBusinessOperatorsData($business),
            'businessCount' => $business->total(),
            'parentCorporationList' => $this->formatCorporationsData($this->corporationRepository->getAllCorporations()),
            'pagination'    => $this->formatPaginationData($business),
        ];

        return $args;
    }

    /**
     * 事業者登録画面
     *
     * @return array
     */
    public function prepareBusinessCreation(): array
    {
        $parentCorporationList = $this->formatCorporationsData($this->corporationRepository->getAllCorporations());
        $args = [
            'parentCorporationList' => $parentCorporationList,
        ];
        return $args;
    }

    /**
     * 事業者申請/事業者/事業者設定の登録
     *
     * @return void
     */
    public function createBusiness(Request $request): void
    {
        // 事業者申請登録
        $applicationData = $request->only([
            'email',
            'corporationId',
            'corporationName',
            'applicantName',
            'applicantNameKana',
            'businessName',
            'zipCode',
            'prefCode',
            'city',
            'phone',
            'invoice',
            'businessForm',
            'notes',
        ]);
        $applicationData['corporation_application_id'] = $applicationData['corporationId'];
        $applicationData['application_status'] = 2;
        $applicationId = $this->businessOperatorRepository->createBusinessApplicationByRequest($this->convertToSnakeCase($applicationData))->business_application_id;

        // 事業者登録
        $businessData = $request->only([
            'corporationId',
            'corporationName',
            'businessName',
            'businessForm',
            'firstDeskNumber',
            'secondDeskNumber',
            'email',
            'password',
            'phone',
            'zipCode',
            'prefCode',
            'city',
            'invoice',
            'businessDescription',
            'businessStatus',
            'notes',
        ]);
        $businessData['business_application_id'] = $applicationId;
        $businessData['password'] = Hash::make($businessData['password']);
        $businessId = $this->businessOperatorRepository->createBusinessByRequest($this->convertToSnakeCase($businessData))->business_id;

        // 事業者設定登録
        $settingData = $request->only([
            'isPublish',
        ]);
        $settingData['business_id'] = $businessId;
        $settingData['is_media_publish'] = false;
        $settingData['is_review_publish'] = false;
        $settingData['is_staff_ranking_publish'] = false;
        $settingData['is_auto_apply'] = false;
        $settingData['auto_apply_amount'] = 2000;
        $this->businessOperatorRepository->createBusinessSetting($this->convertToSnakeCase($settingData));
    }

    /**
     * 事業者詳細
     *
     * @return array
     */
    public function getBusinessById(int $businessId): array
    {
        $business = $this->businessOperatorRepository->getBusinessDetailsByBusinessId($businessId);
        $businessApplication = $this->businessOperatorRepository->getBusinessApplicationByApplicationId($business->business_application_id);
        $parentCorporationList = $this->formatCorporationsData($this->corporationRepository->getAllCorporations());

        $args = [
            'businessId' => $business->business_id,
            'businessApplicationId' => $business->business_application_id,
            'settingId' => $business->businessSettings->setting_id,
            'email' => $business->email,
            'corporationId' => $business->corporation_id,
            'corporationName' => $business->corporation_name,
            'businessName' => $business->business_name,
            'zipCode' => $business->zip_code,
            'prefCode' => $business->pref_code,
            'city' => $business->city,
            'phone' => $business->phone,
            'invoice' => $business->invoice,
            'businessForm' => $business->business_form,
            'businessDescription' => $business->business_description,
            'firstDeskNumber' => $business->first_desk_number,
            'secondDeskNumber' => $business->second_desk_number,
            'leadCompany' => $business->lead_company,
            'notes' => $business->notes,
            'businessStatus' => $business->business_status,
            'isPublish' => $business->businessSettings->is_publish,
            'applicantName' => $businessApplication->applicant_name, // business_application_idから取得
            'applicantNameKana' => $businessApplication->applicant_name_kana, // business_application_idから取得
            'parentCorporationList' => $parentCorporationList,
        ];

        return $args;
    }

    /**
     * 事業者更新
     *
     * @return void
     */
    public function updateBusiness(Request $request): void
    {
        // 事業者申請登録
        $applicationData = $request->only([
            'businessApplicationId',
            'applicantName',
            'applicantNameKana',
        ]);

        $this->businessOperatorRepository->updateBusinessApplicationByRequest($this->convertToSnakeCase($applicationData));

        // 事業者更新
        $businessData = $request->only([
            'businessId',
            'corporationName',
            'businessName',
            'businessForm',
            'firstDeskNumber',
            'secondDeskNumber',
            'email',
            'password',
            'phone',
            'zipCode',
            'prefCode',
            'city',
            'invoice',
            'businessDescription',
            'businessStatus',
            'notes',
        ]);

        $this->businessOperatorRepository->updateBusinessByRequest($this->convertToSnakeCase($businessData));

        // 事業者設定更新
        $settingData = $request->only([
            'settingId',
            'isPublish',
        ]);
        $settingParam = $this->convertToSnakeCase($settingData);
        $this->businessOperatorRepository->updateBusinessSettingBySettingId($settingParam['setting_id'], $settingParam);
    }

    /**
     * 事業者一覧を整形
     *
     * @param LengthAwarePaginator $businessOperators
     * @return Collection
     */
    private function formatBusinessOperatorsData(LengthAwarePaginator $businessOperators): Collection
    {
        return $businessOperators->map(function ($item) {
            return [
                'businessId' => $item->business_id,
                'businessName' => $item->business_name,
                'corporationName' => $item->corporation_name ?? $item->corporation->corporation_name,
                'businessForm' => $item->business_form,
                'businessStatus' => $item->business_status,
                'isPublish' => $item->businessSettings->is_publish,
            ];
        });
    }

    /**
     * 法人情報を整形
     *
     * @param Collection $corporations
     * @return Collection
     */
    private function formatCorporationsData(Collection $corporations): Collection
    {
        return $corporations->map(function ($item) {
            return [
                'corporationId' => $item->corporation_id,
                'corporationName' => $item->corporation_name,
            ];
        });
    }

    /**
     * ページネーション情報を整形
     *
     * @param LengthAwarePaginator $businessOperators
     * @return array
     */
    private function formatPaginationData(LengthAwarePaginator $businessOperators): array
    {
        return [
            'links' => $businessOperators->linkCollection(),
            'currentPage' => $businessOperators->currentPage(),
            'total' => $businessOperators->total(),
        ];
    }

    /**
     * リクエストデータをスネークケースに変換
     *
     * @param array $request
     * @return array
     */
    private function convertToSnakeCase(array $request): array
    {
        $output = [];
        foreach ($request as $key => $value) {
            $output[Str::snake($key)] = $value;
        }
        return $output;
    }
    public function getReview(int $reviewId): array
    {
        $review = $this->businessOperatorRepository->getReview($reviewId);
        return [
            'reviewId' => $review->review_id,
            'businessId' => $review->business_id,
            'nickname' => $review->user->nickname,
            'comment' => $review->comment,
            'createdAt' => $review->created_at,
        ];
    }
    public function getAllBsuinessReviews(int $businessId): array
    {
        $reviews = $this->businessOperatorRepository->getAllBusinessReviews($businessId);
        $args = $reviews->businessReviews->map(function ($review) {
            return [
                'reviewId' => $review->review_id,
                'businessId' => $review->business_id,
                'nickname' => $review->user->nickname,
                'comment' => $review->comment,
                'createdAt' => $review->created_at,
            ];
        })->toArray();
        $args = [
            'reviews' => $args,
        ];
        return $args;
    }
    public function deleteReviewById(int $reviewId)
    {
        $this->businessOperatorRepository->deleteReviewById($reviewId);
    }
}
