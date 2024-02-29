<?php

namespace App\Repositories\Token;

use App\Enums\EntityType;
use App\Models\Token;
use Carbon\Carbon;

class TokenRepository implements TokenRepositoryInterface
{
    public function find(string $token, string $email): ?Token
    {
        return Token::where(['token' => $token, 'email' => $email])->first();
    }

    public function registerToken(int $entityId, EntityType $entityType, string $email, string $token): void
    {
        Token::create([
            'entity_id'   => $entityId,
            'entity_type' => $entityType,
            'email'       => $email,
            'token'       => $token,
            'end_at'      => Carbon::now()->addHour()
        ]);
    }

    public function deleteToken(string $token): void
    {
        Token::find($token)->delete();
    }

    public function getStaffIdByTokenAndEmail(string $token, string $email): ?int
    {
        $where = [
            ['token', $token],
            ['entity_type', EntityType::Staff],
            ['email', hash('sha256', $email)],
            ['end_at','>=',Carbon::now()],
        ];

        $token = Token::where($where)->first();

        return !is_null($token) ? $token->entity_id : null;
    }

    public function getBusinessIdByToken(string $token): ?int
    {
        $now = Carbon::now();

        $where = [
            ['token', $token],
            ['entity_type', EntityType::BusinessOperator],
            ['end_at','>=',$now],
        ];

        $token = Token::where($where)->first();

        return !is_null($token) ? $token->entity_id : null;
    }

    public function deleteByEntity(EntityType $entityType, int $entityId): void
    {
        Token::where('entity_type', $entityType)
        ->where('entity_id', $entityId)
        ->delete();
    }

    public function getUserIdByTokenAndEmail(string $token, string $email): ?int
    {
        $where = [
            ['token', $token],
            ['entity_type', EntityType::User],
            ['email', $email],
            ['end_at','>=',Carbon::now()],
        ];

        $token = Token::where($where)->first();

        return !is_null($token) ? $token->entity_id : null;
    }

    public function getBusinessIdByTokenAndEmail(string $token, string $email): ?int
    {
        $where = [
            ['token', $token],
            ['entity_type', EntityType::BusinessOperator],
            ['email', $email],
            ['end_at','>=',Carbon::now()],
        ];

        $token = Token::where($where)->first();

        return !is_null($token) ? $token->entity_id : null;
    }

    public function getCorporationIdByTokenAndEmail(string $token, string $email): ?int
    {
        $where = [
            ['token', $token],
            ['entity_type', EntityType::Corporation],
            ['email', $email],
            ['end_at','>=',Carbon::now()],
        ];

        $token = Token::where($where)->first();

        return !is_null($token) ? $token->entity_id : null;
    }
}
