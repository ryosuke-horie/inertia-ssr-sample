<?php

namespace App\Repositories\LoginHistory;

use App\Enums\EntityType;

interface LoginHistoryRepositoryInterface
{
    /**
     * 対象利用者の履歴を削除
     * @param EntityType $entityType
     * @param int $entityId
     * @return void
     */
    public function deleteHistoryByEntity(EntityType $entityType, int $entityId): void;
}
