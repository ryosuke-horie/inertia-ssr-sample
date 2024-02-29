<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankAccount::truncate();

        BankAccount::create([
            'entity_type' => 3, // 事業者
            'entity_id' => 2, // 事業者ID
            'bank_name' => encrypt('サンプル銀行'),
            'branch_name' => encrypt('サンプル支店'),
            'account_type' => encrypt('1'), // 普通預金口座
            'account_number' => encrypt('1234567'),
            'account_holder_name' => encrypt('タナカ タロウ')
        ]);

        BankAccount::create([
            'entity_type' => 4, // 法人
            'entity_id' => 1, // 法人ID
            'bank_name' => encrypt('テスト銀行'),
            'branch_name' => encrypt('テスト支店'),
            'account_type' => encrypt('2'), // 当座預金口座
            'account_number' => encrypt('7654321'),
            'account_holder_name' => encrypt('スズキ ハナコ')
        ]);
    }
}
