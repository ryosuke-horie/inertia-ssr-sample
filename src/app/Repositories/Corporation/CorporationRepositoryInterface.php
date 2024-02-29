<?php

namespace App\Repositories\Corporation;

use App\Models\Corporation;
use App\Models\CorporationApplication;
use App\Models\CorporationSetting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CorporationRepositoryInterface
{
    /**
     * 法人情報取得
     *
     * @param int $corporationId
     * @return Corporation
     */
    public function findByCorporationId(int $corporationId): Corporation;

    /**
     * メールアドレスで事業者取得
     * @param string $emailHash
     */
    public function findByEmailHash(string $emailHash): ?Corporation;

    /**
     * メールアドレス変更
     * @param int $corporationId
     * @param string $email
     * @param string $emailHash
     * @return void
     */
    public function updateEmail(int $corporationId, string $email, string $emailHash): void;

    /**
     * パスワード変更
     * @param int $corporationId
     * @param string $password
     * @return void
     */
    public function updatePassword(int $corporationId, string $password): void;

    /**
     * 設定情報を取得
     * @param int $corporationId
     * @return CorporationSetting
     */
    public function getCorporationSettingByCorporationId(int $corporationId): CorporationSetting;

    /**
     * 設定情報更新
     * @param int $settingId
     * @param array $param
     */
    public function updateCorporationSettingBySettingId(int $settingId, array $param): void;

    /**
     * 法人情報全件取得
     *
     * @return Collection
     */
    public function getAllCorporations(): Collection;

    /**
     * 法人情報全件取得（ページネーション）
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginateCorporations(): LengthAwarePaginator;

    /**
     * 法人情報検索（ページネーション）
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function searchPaginateCorporationByRequest(Request $request): LengthAwarePaginator;

    /**
     * 法人申請登録
     *
     * @param array $request
     * @return CorporationApplication
     */
    public function createCorporationApplicationByRequest(array $request): CorporationApplication;

    /**
     * 法人登録
     *
     * @param array $request
     * @return Corporation
     */
    public function createCorporationByRequest(array $request): Corporation;

    /**
     * 法人設定登録
     *
     * @param array $request
     * @return void
     */
    public function createCorporationSettingByRequest(array $request): void;

    /**
     * 法人詳細情報取得
     *
     * @param int $corporationId
     * @return Corporation
     */
    public function getCorporationDetailsByCorporationId(int $corporationId): Corporation;

    /**
     * 法申請情報取得
     *
     * @param int $applicationId
     * @return CorporationApplication
     */
    public function getCorporationApplicationByApplicationId(int $applicationId): CorporationApplication;

    /**
     * 法人申請情報更新
     *
     * @param array $request
     * @return void
     */
    public function updateCorporationApplicationByRequest(array $request): void;

    /**
     * 法人情報更新
     *
     * @param array $request
     * @return void
     */
    public function updateCorporationByRequest(array $request): void;
}
