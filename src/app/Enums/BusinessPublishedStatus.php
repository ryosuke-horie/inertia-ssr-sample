<?php

namespace App\Enums;

enum BusinessPublishedStatus: int
{
    case Published = 1;
    case Unpublished = 2;

    public function getName(): string
    {
        return match ($this) {
            self::Published => '公開',
            self::Unpublished => '非公開',
        };
    }
}
