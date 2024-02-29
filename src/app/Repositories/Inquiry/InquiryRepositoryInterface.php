<?php

namespace App\Repositories\Inquiry;

use App\Enums\EntityType;

interface InquiryRepositoryInterface
{
    /**
     * お問い合わせ情報を登録する
     *
     * @param string $name
     * @param string $email
     * @param string $content
     * @param EntityType $entityType
     * @param int|null $userId
     * @return void
     */
    public function storeInquiry(string $name, string $email, string $content, EntityType $entityType, int|null $userId): void;
}
