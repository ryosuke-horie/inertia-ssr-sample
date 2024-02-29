<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\CorporationService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewEmailController extends Controller
{
    private CorporationService $corporationService;

    public function __construct(CorporationService $corporationService)
    {
        $this->corporationService = $corporationService;
    }

    public function check(Request $request): RedirectResponse
    {
        $token = $request->input('token');
        $email = $request->input('email');

        try {
            $this->corporationService->updateEmail($token, $email);
            session(['previousName' => 'corporation.change-email.check']);
            return Redirect::route('corporation.change-email.complete');
        } catch (\Exception $e) {
            return Redirect::route('corporation.login');
        }
    }

    public function complete(Request $request): Response|RedirectResponse
    {
        // /user/change-emailからのリダイレクト以外はログインページに遷移
        $previousUrl = session('previousName');
        session()->forget('previousName');

        if ($previousUrl !== 'corporation.change-email.check') {
            return Redirect::route('corporation.login');
        }

        return Inertia::render('Corporation/Auth/ChangeEmail/Complete');
    }
}
