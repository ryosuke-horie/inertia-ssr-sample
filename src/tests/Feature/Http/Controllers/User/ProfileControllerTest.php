<?php

namespace Tests\Feature\Http\Controllers\User;

use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    #[Test]
    public function ユーザープロフィール画面へのアクセス時のステータスコードをテスト()
    {
        // ログイン前
        $response = $this->get('/user/profile');
        $response->assertRedirectToRoute('user.login');

        // ログイン後
        $this->loginAsUser();
        $response = $this->get('/user/profile');
        $response->assertStatus(200);
    }

    #[Test]
    public function ユーザープロフィール画面のInetiaレスポンスをテスト()
    {
        $this->loginAsUser();
        $response = $this->get('/user/profile');
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('User/Profile/Show')
                ->has('userId')
                ->has('nickname')
                ->has('birthdate')
                ->has('isShowRanking')
                ->has('userProfileImage')
        );
    }

    #[Test]
    public function ユーザープロフィール画像のアップロードAPIをテスト()
    {
        $this->loginAsUser();

        // テスト用の画像をAPIを利用してアップロード
        $response = $this->post('/api/user/profile/image', [
            'image' => UploadedFile::fake()->image('profile.jpg')
        ]);

        // レスポンスのステータスコードが200であること
        $response->assertStatus(200);
        // レスポンスのJSONにuserProfileImageが含まれていること
        $response->assertJsonStructure(['userProfileImage']);
    }
}
