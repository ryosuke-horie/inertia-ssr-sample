<?php

namespace App\Http\Controllers\BusinessOperator\Auth;

use App\Http\Controllers\Controller;
use App\Models\BusinessOperator;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('BusinessOperator/Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // business_application_idは、本来ならrequiredだが一旦nullableにしておく
        $request->validate([
            'email' => 'required|string|email|max:255|unique:business_operators,email',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'corporation_id' => 'nullable|exists:corporations,corporation_id',
            'business_application_id' => 'nullable|exists:business_applications,business_application_id',
            'corporation_name' => 'nullable|string|max:100',
            'applicant_name' => 'nullable|string|max:255',
            'applicant_name_kana' => 'nullable|string|max:255',
            'business_name' => 'required|string|max:100',
            'zip_code' => 'required|string|max:7',
            'pref_code' => 'required|string|max:2',
            'city' => 'required|string|max:200',
            'phone' => 'required|string|max:255',
            'invoice_reg_num' => 'required|string|max:255',
            'business_form' => 'nullable|string|max:50',
            'business_description' => 'nullable|string|max:300',
            'first_desk_number' => 'nullable|integer',
            'second_desk_number' => 'nullable|integer',
        ]);

        $bussinessOperation = BusinessOperator::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'corporation_id' => $request->corporation_id,
            'business_application_id' => $request->business_application_id,
            'corporation_name' => $request->corporation_name,
            'applicant_name' => $request->applicant_name,
            'applicant_name_kana' => $request->applicant_name_kana,
            'business_name' => $request->business_name,
            'zip_code' => $request->zip_code,
            'pref_code' => $request->pref_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'invoice_reg_num' => $request->invoice_reg_num,
            'business_form' => $request->business_form,
            'business_description' => $request->business_description,
            'first_desk_number' => $request->first_desk_number,
            'second_desk_number' => $request->second_desk_number,
        ]);

        event(new Registered($bussinessOperation));

        Auth::login($bussinessOperation);

        return redirect(RouteServiceProvider::BUSSINESS_OPERATOR_HOME);
    }
}
