<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransferRequestCancellation;

class TransferRequestCancellationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferRequestCancellation::truncate();

        TransferRequestCancellation::create([
            'request_id' => 1, // 既存の振込申請ID
            'cancel_reason' => '不要になったため'
        ]);

        TransferRequestCancellation::create([
            'request_id' => 2, // 別の振込申請ID
            'cancel_reason' => '誤った申請のため'
        ]);
    }
}
