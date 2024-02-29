<?php

namespace App\Repositories\LoginHistory;

use App\Models\LoginHistory;
use App\Enums\EntityType;

class LoginHistoryRepository implements LoginHistoryRepositoryInterface
{
    public function deleteHistoryByEntity(EntityType $entityType, int $entityId): void
    {
        LoginHistory::where('entity_type', $entityType)
        ->where('entity_id', $entityId)
        ->delete();
    }
}
