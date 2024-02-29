<?php

namespace App\Services\Admin;

use App\Repositories\Corporation\CorporationRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CorporationService
{
    private CorporationRepositoryInterface $corporationRepository;

    public function __construct(CorporationRepositoryInterface $corporationRepository)
    {
        $this->corporationRepository = $corporationRepository;
    }

    /**
     * 事業者一覧を取得
     *
     * @return array
     */
    public function getAllCorporations(): array
    {
        $corporations = $this->corporationRepository->getAllPaginateCorporations();

        $args = [
            'corporationList' => $this->formatCorporationData($corporations),
            'corporationCount' => $corporations->total(),
            'pagination' => $this->formatPaginationData($corporations),
        ];

        return $args;
    }

    /**
     * 法人検索
     *
     * @param Request $request
     * @return array
     */
    public function searchCorporations(Request $request): array
    {
        $corporations = $this->corporationRepository->searchPaginateCorporationByRequest($request);

        $args = [
            'corporationList' => $this->formatCorporationData($corporations),
            'corporationCount' => $corporations->total(),
            'pagination' => $this->formatPaginationData($corporations),
        ];

        return $args;
    }

    /**
     * 法人登録
     *
     * @param Request $request
     * @return void
     */
    public function createCorporation(Request $request): void
    {
        // 法人申請登録
        $applicationData = $request->only([
            'email',
            'corporationName',
            'applicantName',
            'applicantNameKana',
            'zipCode',
            'prefCode',
            'city',
            'phone',
            'invoice',
        ]);
        $applicationData['application_status'] = 2;
        $applicationId = $this->corporationRepository->createCorporationApplicationByRequest($this->convertToSnakeCase($applicationData))->corporation_application_id;

        // 法人登録
        $corporationData = $request->only([
            'email',
            'password',
            'corporationName',
            'zipCode',
            'prefCode',
            'city',
            'phone',
            'invoice',
            'notes',
        ]);
        $corporationData['corporation_application_id'] = $applicationId;
        $corporationData['password'] = Hash::make($corporationData['password']);
        $corporationId = $this->corporationRepository->createCorporationByRequest($this->convertToSnakeCase($corporationData))->corporation_id;

        // 法人設定登録
        $settingData = [];
        $settingData['corporation_id'] = $corporationId;
        $settingData['is_auto_apply'] = false;
        $settingData['auto_apply_amount'] = 2000;
        $this->corporationRepository->createCorporationSettingByRequest($this->convertToSnakeCase($settingData));
    }

    /**
     * 法人詳細情報を取得
     *
     * @param int $corporationId
     * @return array
     */
    public function getCorporationById(int $corporationId): array
    {
        $corporation = $this->corporationRepository->getCorporationDetailsByCorporationId($corporationId);
        $application = $this->corporationRepository->getCorporationApplicationByApplicationId($corporation->corporation_application_id);

        // 法人に紐づいている事業者のidとnameを抽出して配列に格納する
        $businessList = $corporation->businessOperators->map(function ($business) {
            return [
                'businessId' => $business->business_id,
                'businessName' => $business->business_name,
            ];
        })->all();

        $args = [
            'corporationId' => $corporation->corporation_id,
            'corporationApplicationId' => $corporation->corporation_application_id,
            'corporationName' => $corporation->corporation_name,
            'email' => $corporation->email,
            'zipCode' => $corporation->zip_code,
            'prefCode' => $corporation->pref_code,
            'city' => $corporation->city,
            'phone' => $corporation->phone,
            'invoice' => $corporation->invoice,
            'applicantName' => $application->applicant_name,
            'applicantNameKana' => $application->applicant_name_kana,
            'notes' => $corporation->notes,
            'businessList' => $businessList,
        ];

        return $args;
    }

    /**
     * 法人情報を更新
     *
     * @param Request $request
     * @return void
     */
    public function updateCorporation(Request $request)
    {

        // 事業者申請登録
        $applicationData = $request->only([
            'corporationApplicationId',
            'applicantName',
            'applicantNameKana',
        ]);

        $this->corporationRepository->updateCorporationApplicationByRequest($this->convertToSnakeCase($applicationData));

        // 事業者更新
        $businessData = $request->only([
            'corporationId',
            'email',
            'password',
            'corporationName',
            'zipCode',
            'prefCode',
            'city',
            'phone',
            'invoice',
            'notes',
        ]);
        $this->corporationRepository->updateCorporationByRequest($this->convertToSnakeCase($businessData));
    }

    /**
     * 法人一覧を整形
     *
     * @param LengthAwarePaginator $corporations
     * @return Collection
     */
    private function formatCorporationData(LengthAwarePaginator $corporations): Collection
    {
        return $corporations->map(function ($corporation) {
            return [
                'corporationId' => $corporation->corporation_id,
                'corporationName' => $corporation->corporation_name,
                'email' => $corporation->email,
                'businessCount' => $corporation->businessOperators->count(), // 事業者の数を追加
            ];
        });
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
}
