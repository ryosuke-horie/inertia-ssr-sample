<?php

namespace App\Http\Controllers\User\Auth;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Trais\StripeTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    use StripeTrait;

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        // Auth0で登録した場合、セッションに保存された情報を取得できる
        $auth0User = session('auth0') ?? null;
        if ($auth0User) {
            return Inertia::render('User/Auth/Register', [
                'email' => $auth0User['email'] ?? '', // providerによってはemailが取得できない場合がある
            ]);
        }

        return Inertia::render('User/Auth/Register');
    }

    /**
     * 新規登録
     * stripeの顧客情報の登録・Auth0の認証・Usersの認証Guardにログイン
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'nickname' => $request->nickname,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'auth0_user_id' => session('auth0')['userId'] ?? null, // Auth0のユーザーIDを保存
        ]);

        // Auth0ユーザー情報のセッションはこれ以降は不要なので削除
        session()->forget('auth0');

        // 新規登録する時にStripeの顧客情報を作成
        $customer = $this->storeCustomer($user->user_id, $request->nickname, $request->email);
        $user->stripe_id = $customer->id;
        $user->save();

        event(new Registered($user));

        // Usersの認証Guardにログイン
        Auth::shouldUse('user');

        Auth::login($user);

        return redirect(RouteServiceProvider::USER_HOME);
    }
}
