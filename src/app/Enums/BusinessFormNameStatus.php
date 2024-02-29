<?php

namespace App\Enums;

enum BusinessFormNameStatus: int
{
    case Restaurant = 1;
    case Zoo = 2;
    case Aquarium = 3;

    public function getName(): string
    {
        return match ($this) {
            self::Restaurant => '飲食店',
            self::Zoo => '動物園',
            self::Aquarium => '水族館',
        };
    }
}
