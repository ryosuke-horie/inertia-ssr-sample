<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\TotalTip;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRankingControllerTest extends TestCase
{
    #[Test]
    public function 未ログイン時にログイン画面にリダイレクトされることをテスト(): void
    {
        $response = $this->get('/mits-admin/user-ranking');
        $response->assertRedirectToRoute('mits-admin.login');

        $response = $this->get('/mits-admin/user-ranking/2024-01');
        $response->assertRedirectToRoute('mits-admin.login');
    }

    #[Test]
    public function ログイン時は正常なステータスコードが返ることをテスト(): void
    {
        // Mits管理者権限でログイン
        $this->loginAsMitsAdmin();

        // パラメーターなしの場合
        $normalResponse = $this->get('/mits-admin/user-ranking');
        $normalResponse->assertOk();

        // パラメーターとして年月を指定する場合
        $searchedResponse = $this->get('/mits-admin/user-ranking/2024-01');
        $searchedResponse->assertOk();
    }

    #[Test]
    public function 年月を指定しない場合のInertiaレスポンスのテスト(): void
    {
        // Arrange - テストデータの準備
        TotalTip::factory()->count(50)->create();

        // Mits管理者権限でログイン
        $this->loginAsMitsAdmin();

        $this->get('/mits-admin/user-ranking')
            ->assertInertia(
                fn (Assert $page) => $page
                ->component('Admin/UserRanking/Index')
                // レスポンスに含まれる配列のキーを確認
                ->has('yearMonth')
                ->has('yearMonthOptions')
                ->has('userRanking')
                // ユーザーランキングのデータの中身を確認
                ->has(
                    'userRanking.0',
                    fn (Assert $page) => $page
                    -> hasAll(['userId', 'nickname', 'totalAmount'])
                )
            );
    }

    #[Test]
    public function 年月をパラメータで渡した場合のInertiaレスポンスのテスト()
    {
        // Arrange - テストデータの準備
        // 3ヶ月以内のデータを50件作成
        TotalTip::factory()->within3Months()->count(50)->create();
        // 当月の年月をURLパラメータで渡す用意
        $date = date('Y-m');
        $url = "/mits-admin/user-ranking/{$date}";

        // Mits管理者権限でログイン
        $this->loginAsMitsAdmin();

        $this->get($url)
            ->assertInertia(
                fn (Assert $page) => $page
                ->component('Admin/UserRanking/Index')
                // レスポンスに含まれる配列のキーを確認
                ->has('yearMonth')
                ->has('yearMonthOptions')
                ->has('userRanking')
                // ユーザーランキングのデータの中身を確認
                ->has(
                    'userRanking.0',
                    fn (Assert $page) => $page
                    -> hasAll(['userId', 'nickname', 'totalAmount'])
                )
            );
    }
}
