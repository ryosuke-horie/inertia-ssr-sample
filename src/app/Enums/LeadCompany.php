<?php

namespace App\Enums;

// 営業種別
enum LeadCompany: int
{
    case Mits = 1;  // マミヤITソリューションズ株式会社
    case Fs = 2; // エフエス株式会社
    case Out = 3; // 外部営業

    public function getName(): string
    {
        return match ($this) {
            self::Mits => 'マミヤITソリューションズ株式会社',
            self::Fs => 'エフエス株式会社',
            self::Out => '外部営業',
        };
    }
}
