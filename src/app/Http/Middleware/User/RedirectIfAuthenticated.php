<?php

namespace App\Http\Middleware\User;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && $guard === 'user') {
                if (Auth::guard($guard)->user()->id == 1) {
                    // ゲストユーザー用の場合
                    return redirect(RouteServiceProvider::USER_GUEST_HOME);
                }
                // 会員のユーザーの場合
                return redirect(RouteServiceProvider::USER_HOME);
            }
        }

        return $next($request);
    }
}
