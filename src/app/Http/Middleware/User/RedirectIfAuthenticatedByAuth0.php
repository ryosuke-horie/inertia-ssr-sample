<?php

namespace App\Http\Middleware\User;

use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticatedByAuth0
{
    /**
     * Auth0による認証の場合の挙動の制御を行う。
     * /user/register（会員登録画面）の処理の前に実行されることを想定。
     * Auth0のSDKが提供するガードを利用してAuth0のユーザー情報を取得し
     * 会員登録もしくはログイン処理を実行する。
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Auth0のユーザー情報を取得
        $auth0User = auth('auth0-session')->user();

        // Auth0のユーザー情報が取得できなかった場合はそのままリダイレクトする
        if (null === $auth0User) {
            return $next($request);
        }

        $userId = $auth0User->sub ?? '';
        $email = $auth0User->email ?? '';

        // auth0_user_idでUsersテーブルを検索し、登録済みならログイン処理を行ってリダイレクトする。
        $user = \App\Models\User::where('auth0_user_id', $userId)->first();
        if ($user) {
            Auth::guard('user')->login($user);
            return redirect(RouteServiceProvider::USER_HOME);
        }

        // Auth0の必要な情報をセッションに保存し、auth0を認証から外す
        session(['auth0' => [
            'userId' => $userId,
            'email' => $email,
        ]]);

        // /user/resister（会員登録画面）へ遷移しDBへ保存させる
        return $next($request);
    }
}
