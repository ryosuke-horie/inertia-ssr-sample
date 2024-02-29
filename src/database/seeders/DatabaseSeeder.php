<?php

namespace Database\Seeders;

use Database\Seeders\AdminSeeder;
use Database\Seeders\AdminStaffSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\UserProfileImageSeeder;
use Database\Seeders\CorporationApplicationSeeder;
use Database\Seeders\CorporationSeeder;
use Database\Seeders\BusinessApplicationSeeder;
use Database\Seeders\BusinessOperatorSeeder;
use Database\Seeders\BusinessProfileImageSeeder;
use Database\Seeders\StaffSeeder;
use Database\Seeders\StaffSchedulesSeeder;
use Database\Seeders\StaffProfileImagesSeeder;
use Database\Seeders\StaffFavoritesSeeder;
use Database\Seeders\StaffNotificationsSeeder;
use Database\Seeders\UserLikesSeeder;
use Database\Seeders\PointBuyHistoriesSeeder;
use Database\Seeders\PointUsageHistoriesSeeder;
use Database\Seeders\UserTipsSeeder;
use Database\Seeders\PointFluctuationHistoriesSeeder;
use Database\Seeders\StaffRepliesSeeder;
use Database\Seeders\ReplyMediaSeeder;
use Database\Seeders\NotificationsSeeder;
use Database\Seeders\NotificationReadSeeder;
use Database\Seeders\PrivateNotificationsSeeder;
use Database\Seeders\TippingAmountSettingsSeeder;
use Database\Seeders\BusinessTippingAmountSettingsSeeder;
use Database\Seeders\BusinessSettingsSeeder;
use Database\Seeders\CorporationSettingsSeeder;
use Database\Seeders\BankAccountsSeeder;
use Database\Seeders\TransferRequestsSeeder;
use Database\Seeders\TransferRequestCancellationsSeeder;
use Database\Seeders\InquiriesSeeder;
use Database\Seeders\NgWordsSeeder;
use Database\Seeders\TotalTipsSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminsSeeder::class, // 管理者データ
            UserSeeder::class, // ユーザーデータ
            UserProfileImageSeeder::class, // ユーザープロフィール画像データ
            CorporationApplicationSeeder::class, // 法人申込申請データ
            CorporationSeeder::class, // 法人データ
            BusinessApplicationSeeder::class, // 事業者申込申請データ
            BusinessOperatorSeeder::class, // 事業者データ
            BusinessProfileImageSeeder::class, // 事業者データ
            StaffSeeder::class, // スタッフデータ
            StaffProfileImagesSeeder::class, // スタッフプロフィール画像データ
            StaffFavoritesSeeder::class, // スタッフお気に入りデータ
            AdminStaffSeeder::class, // 管理者スタッフデータ
            AdminStaffStaffSeeder::class, // 管理者スタッフ・スタッフ中間テーブルデータ
            UserLikesSeeder::class, // ユーザーいいねデータ

            PointBuyHistoriesSeeder::class, // ポイント購入履歴データ
            UserTipsSeeder::class, // 投げ銭データ
            PointUsageHistoriesSeeder::class, // ポイント利用履歴データ
            PointFluctuationHistoriesSeeder::class, // ポイント変動履歴データ

            StaffRepliesSeeder::class, // スタッフ返信データ
            ReplyMediaSeeder::class, // 返信メディアデータ

            NotificationsSeeder::class, // お知らせデータ
            NotificationReadSeeder::class, // お知らせ既読データ
            PrivateNotificationsSeeder::class, // 個別お知らせデータ

            TippingAmountSettingsSeeder::class, // 投げ銭金額設定データ
            BusinessTippingAmountSettingsSeeder::class, // 事業者投げ銭金額設定データ
            BusinessSettingsSeeder::class, // 事業者設定データ
            CorporationSettingsSeeder::class, // 法人設定データ

            BankAccountsSeeder::class, // 銀行口座データ
            TransferRequestsSeeder::class, // 振込申請データ
            TransferRequestCancellationsSeeder::class, // 振込申請取り消しデータ (振込申請に問題がある場合に必要なので、データ挿入時に注意が必要)
            StaffSchedulesSeeder::class, // スタッフスケジュールデータ
            StaffNotificationsSeeder::class, // スタッフ通知データ

            InquiriesSeeder::class, // お問い合わせデータ
            NgWordsSeeder::class, // NGワードデータ
            TotalTipsSeeder::class, // 累計投げ銭データ
        ]);
    }
}
