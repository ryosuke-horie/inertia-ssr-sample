<?php

namespace App\Repositories\Admin;

use App\Models\Admin;
use App\Models\TippingAmountSetting;
use Illuminate\Support\Collection;

class AdminRepository implements AdminRepositoryInterface
{
    public function getAllTippingAmountSetting(): Collection
    {
        return TippingAmountSetting::get();
    }

    public function getFreeAmountForAmount(int $amount): int
    {
        $tippingAmountSetting = TippingAmountSetting::where('amount', $amount)->first();
        return $tippingAmountSetting->free_amount;
    }
}
