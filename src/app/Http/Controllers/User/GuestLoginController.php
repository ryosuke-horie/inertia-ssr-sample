<?php

namespace App\Http\Controllers\User;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Services\GuestUserService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Trais\StripeTrait;

class GuestLoginController extends Controller
{
    use StripeTrait;

    protected $guestUserService;

    /**
     * @param  GuestUserService  $guestUserService
     * @return void
     */
    public function __construct(GuestUserService $guestUserService)
    {
        $this->guestUserService = $guestUserService;
    }

    /**
     * ゲストユーザーでログイン
     *
     * @return RedirectResponse
     */
    public function guestLogin(Request $request): RedirectResponse
    {
        // テスト用ユーザーの情報を取得
        $user = $this->guestUserService->getGuestUser();


        if (!$user) {
            $email = 'guest@example.com';
            $nickname = 'ゲストユーザー';
            $user = $this->guestUserService->createGuest([
                'email'  => $email,
                'password'  => bcrypt('password123'),
                'email_verified_at' => Carbon::now(),
                'nickname' => $nickname,
                'birthdate' => '1990-01-01',
            ]);

            $customer = $this->storeCustomer($user->user_id, $nickname, $email);

            $user->stripe_id = $customer->id;
            $user->save();

            event(new Registered($user));
        }

        $request->session()->regenerate();

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::USER_GUEST_HOME);
    }
}
