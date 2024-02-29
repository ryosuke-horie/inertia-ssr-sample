<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransferRequest;
use Carbon\Carbon;

class TransferRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransferRequest::truncate();

        // 現在の月の最初と最後の日付を取得
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m');

        TransferRequest::create([
            'entity_type' => 3, // 事業者
            'entity_id' => 2, // 事業者ID
            'request_status' => 2, // 未振込
            'notification_number' => '000000001',
            'transfer_request_amount' => 2000.00,
            'payment_fee_rate' => 3.6,
            'usage_fee_rate' => 20.0,
            'transfer_fee_amount' => 316.00,
            'transfer_amount' => 1228.00,
            'transaction_period_from' => $startOfMonth,
            'transaction_period_to' => $endOfMonth,
            'confirm_bank_name' => encrypt('サンプル銀行'),
            'confirm_branch_name' => encrypt('サンプル支店'),
            'confirm_account_type' => encrypt('1'),
            'confirm_account_number' => encrypt('1234567'),
            'confirm_account_holder_name' => encrypt('タナカ タロウ')
        ]);

        TransferRequest::create([
            'entity_type' => 4, // 法人
            'entity_id' => 1, // 法人ID
            'request_status' => 2, // 未振込
            'notification_number' => '000000002',
            'transfer_request_amount' => 3000.00,
            'payment_fee_rate' => 3.6,
            'usage_fee_rate' => 20.0,
            'transfer_fee_amount' => 316.00,
            'transfer_amount' => 2250.00,
            'transaction_period_from' => $startOfMonth,
            'transaction_period_to' => $endOfMonth,
            'confirm_bank_name' => encrypt('テスト銀行'),
            'confirm_branch_name' => encrypt('テスト支店'),
            'confirm_account_type' => encrypt('2'),
            'confirm_account_number' => encrypt('7654321'),
            'confirm_account_holder_name' => encrypt('スズキ ハナコ')
        ]);
    }
}
