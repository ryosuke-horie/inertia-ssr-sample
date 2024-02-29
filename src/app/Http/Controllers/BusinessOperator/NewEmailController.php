<?php

namespace App\Http\Controllers\BusinessOperator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\BusinessOperatorService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewEmailController extends Controller
{
    private BusinessOperatorService $businessOperatorService;

    public function __construct(BusinessOperatorService $businessOperatorService)
    {
        $this->businessOperatorService = $businessOperatorService;
    }

    public function check(Request $request): RedirectResponse
    {
        $token = $request->input('token');
        $email = $request->input('email');

        try {
            $this->businessOperatorService->updateEmail($token, $email);
            session(['previousName' => 'business-operator.change-email.check']);
            return Redirect::route('business-operator.change-email.complete');
        } catch (\Exception $e) {
            return Redirect::route('business-operator.login');
        }
    }

    public function complete(Request $request): Response|RedirectResponse
    {
        // /user/change-emailからのリダイレクト以外はログインページに遷移
        $previousUrl = session('previousName');
        session()->forget('previousName');

        if ($previousUrl !== 'business-operator.change-email.check') {
            return Redirect::route('business-operator.login');
        }

        return Inertia::render('BusinessOperator/Auth/ChangeEmail/Complete');
    }
}
