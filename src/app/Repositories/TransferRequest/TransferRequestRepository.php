<?php

namespace App\Repositories\TransferRequest;

use App\Models\TransferRequest;
use App\Models\TransferRequestCancellation;
use Illuminate\Support\Collection;
use App\Enums\EntityType;

class TransferRequestRepository implements TransferRequestRepositoryInterface
{
    public function getTransferRequestByEntity(EntityType $entityType, int $entityId): Collection
    {
        return TransferRequest::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->get();
    }

    public function getTransferRequestByRequestId(int $requestId): TransferRequest
    {
        return TransferRequest::find($requestId);
    }

    public function getListByEntityDate(EntityType $entityType, int $entityId, string $date): bool
    {
        return TransferRequest::where('entity_type', $entityType)
        ->where('entity_id', $entityId)
        ->where(function ($query) use ($date) {
            $query->where('transaction_period_from', '<=', $date)
            ->where('transaction_period_to', '>=', $date);
        })
        ->exists();
    }

    public function getTransferRequestByEntityDesc(EntityType $entityType, int $entityId): Collection
    {
        return TransferRequest::where('entity_type', $entityType)
        ->where('entity_id', $entityId)
        ->orderBy('request_id', 'DESC')
        ->get();
    }

    public function updateBankByEntity(EntityType $entityType, int $entityId): void
    {
        TransferRequest::where('entity_type', $entityType)
        ->where('entity_id', $entityId)
        ->update([
            'confirm_bank_name'             => null,
            'confirm_branch_name'           => null,
            'confirm_account_type'          => null,
            'confirm_account_number'        => null,
            'confirm_account_holder_name'   => null
        ]);
    }
}
