<?php

declare(strict_types=1);

namespace App\Trais;

trait EmailTrait
{
    /**
     * メールアドレスを小文字にする
     *
     * @param string $email
     * @return string
     */
    private function convertToLowercase(string $email): string
    {
        $parts = explode('@', $email);

        if (count($parts) == 2) {
            $localPart = strtolower($parts[0]);
            $domainPart = strtolower($parts[1]);
            $email = $localPart . '@' . $domainPart;
        }

        return $email;
    }
}
