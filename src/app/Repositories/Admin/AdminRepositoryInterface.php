<?php

namespace App\Repositories\Admin;

use App\Models\TippingAmountSetting;
use Illuminate\Support\Collection;

interface AdminRepositoryInterface
{
    /**
     * 投げ銭金額設定を全て取得
     *
     * @return Collection
     */
    public function getAllTippingAmountSetting(): Collection;

    /**
     * 指定した金額に対する無償ポイントを取得
     *
     * @param int $amount
     * @return int
     */
    public function getFreeAmountForAmount(int $amount): int;
}
