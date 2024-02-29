<?php

namespace App\Repositories\Inquiry;

use App\Enums\EntityType;
use App\Models\Inquiry;

class InquiryRepository implements InquiryRepositoryInterface
{
    public function storeInquiry(string $name, string $email, string $content, EntityType $entityType, int|null $userId): void
    {
        Inquiry::create([
            'name' => $name,
            'email' => $email,
            'content' => $content,
            'entity_type' => $entityType,
            'entity_id' => $userId,
            'status' => '1', // 未対応
        ]);
    }
}
