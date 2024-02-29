<?php

namespace App\Http\Middleware\User;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOnlyAccessMiddleware
{
    /**
     * 会員ユーザーのみアクセス許可
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_id === 1) {
            // ユーザーIDが1の場合、定義されたリダイレクト先へ
            return redirect(RouteServiceProvider::USER_GUEST_HOME);
        }

        // ユーザーIDが1でない場合、または未認証の場合は、通常の処理を続行
        return $next($request);
    }
}
