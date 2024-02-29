<?php

namespace App\Repositories\Token;

use App\Enums\EntityType;
use App\Models\BusinessOperator;
use App\Models\Token;

interface TokenRepositoryInterface
{
    /**
     * トークン管理追加
     * @param int $entityId
     * @param EntityType $entityType
     * @param string $email
     * @param string $token
     * @return void
     */
    public function registerToken(int $entityId, EntityType $entityType, string $email, string $token): void;

    /**
     * トークン管理削除
     * @param string $token
     * @return void
     */
    public function deleteToken(string $token): void;

    /**
     * BusinessId(EntityId)取得
     * @param string $token
     * @return int $entityId
     */
    public function getBusinessIdByToken(string $token): ?int;

    /**
     * 対象利用者のデータを削除
     * @param EntityType $entityType
     * @param int $entityId
     * @return void
     */
    public function deleteByEntity(EntityType $entityType, int $entityId): void;

    /**
     * UserId(EntityId)取得
     * @param string $token
     * @return int $entityId
     */
    public function getUserIdByTokenAndEmail(string $token, string $email): ?int;

    /**
     * staffId(EntityId)取得
     * @param string $token
     * @param string $email
     * @return int $entityId
     */
    public function getStaffIdByTokenAndEmail(string $token, string $email): ?int;

    /**
     * BusinessId(EntityId)取得
     * @param string $token
     * @return int $entityId
     */
    public function getBusinessIdByTokenAndEmail(string $token, string $email): ?int;

    /**
     * CorporationId(EntityId)取得
     * @param string $token
     * @return int $entityId
     */
    public function getCorporationIdByTokenAndEmail(string $token, string $email): ?int;
}
