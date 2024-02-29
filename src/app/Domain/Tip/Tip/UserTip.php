<?php

declare(strict_types=1);

namespace App\Domain\Tip\Tip;

class UserTip
{
    public readonly int $tipId;
    public readonly int $userId;
    public readonly int $staffId;
    public readonly ?string $image;
    public readonly string $createdAt;
    public readonly string $userName;
    public readonly int $points;
    public readonly ?string $message;
    public readonly bool $isRead;
    public readonly bool $isResponse;

    public function __construct(
        int $tipId,
        int $userId,
        int $staffId,
        ?string $image,
        string $createdAt,
        string $userName,
        int $points,
        ?string $message,
        bool $isRead,
        bool $isResponse,
    ) {
        $this->tipId      = $tipId;
        $this->userId     = $userId;
        $this->staffId    = $staffId;
        $this->image      = $image;
        $this->createdAt  = $createdAt;
        $this->userName   = $userName;
        $this->points     = $points;
        $this->message    = $message;
        $this->isRead     = $isRead;
        $this->isResponse = $isResponse;
    }
}
