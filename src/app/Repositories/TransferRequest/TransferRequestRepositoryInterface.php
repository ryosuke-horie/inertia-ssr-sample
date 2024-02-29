<?php

namespace App\Repositories\TransferRequest;

use App\Enums\EntityType;
use Illuminate\Support\Collection;
use App\Models\TransferRequest;

interface TransferRequestRepositoryInterface
{
    /**
     * 対象利用者の振込申請を取得
     * @param EntityType $entityType
     * @param int $entityId
     * @return Collection
     */
    public function getTransferRequestByEntity(EntityType $entityType, int $entityId): Collection;

    /**
     * RequestIdで取得
     * @param int $requestId
     * @return TransferRequest
     */
    public function getTransferRequestByRequestId(int $requestId): TransferRequest;

    /**
     * 対象年月に該当するデータがあるかチェック
     * @param EntityType $entityType
     * @param int $entityId
     * @param string $date
     * @return bool
     */
    public function getListByEntityDate(EntityType $entityType, int $entityId, string $date): bool;

    /**
     * 対象利用者の振込申請を取得(降順取得)
     * @param EntityType $entityType
     * @param int $entityId
     * @return Collection
     */
    public function getTransferRequestByEntityDesc(EntityType $entityType, int $entityId): Collection;

    /**
     * 対象利用者の口座情報を削除
     * @param EntityType $entityType
     * @param int $entityId
     * @return void
     */
    public function updateBankByEntity(EntityType $entityType, int $entityId): void;
}
