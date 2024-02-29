<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\BusinessApplicationService;
use App\Services\BusinessSettingService;
use App\Services\BusinessOperatorService;
use App\Http\Requests\Corporation\CreateBusinessOperatorRequest;
use Illuminate\Support\Facades\Session;
use App\Enums\Prefecture;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class BusinessOperatorController extends Controller
{
    use FlashMessageTrait;

    private BusinessApplicationService $businessApplicationService;
    private BusinessSettingService $businessSettingService;
    private BusinessOperatorService $businessOperatorService;

    public function __construct(
        BusinessApplicationService $businessApplicationService,
        BusinessSettingService $businessSettingService,
        BusinessOperatorService $businessOperatorService
    ) {
        $this->businessApplicationService = $businessApplicationService;
        $this->businessSettingService = $businessSettingService;
        $this->businessOperatorService = $businessOperatorService;
    }

    /**
     * 選択画面表示
     * @return Response
     */
    public function index()
    {
        return Inertia::render('Corporation/BusinessOperator/Index');
    }

    /**
     * 店舗管理：一覧画面表示
     * @return Response
     */
    public function managementIndex()
    {
        $businessApplications = $this->businessApplicationService->management(Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/BusinessOperator/Management/Index', [
            'businessApplications'  => $businessApplications
        ]);
    }

    /**
     * 店舗管理：詳細画面表示
     * @param int $businessApplicationId
     * @return Response
     */
    public function managementShow(int $businessApplicationId): Response
    {
        $businessApplication = $this->businessApplicationService->managementShow($businessApplicationId);

        return Inertia::render('Corporation/BusinessOperator/Management/Show', [
            'businessApplicationId' => $businessApplication->business_application_id,
            'businessName'          => is_null($businessApplication->businessOperator) ?
                                        $businessApplication->business_name :
                                        $businessApplication->businessOperator->business_name,
            'applicationStatus'     => $businessApplication->application_status,
            'businessId'            => is_null($businessApplication->businessOperator) ? null : $businessApplication->businessOperator->business_id,
        ]);
    }

    /**
     * 店舗管理：登録情報画面
     * @param int $businessApplicationId
     * @return Response
     */
    public function managementInfo(int $businessApplicationId): Response
    {
        $businessApplication = $this->businessApplicationService->managementShow($businessApplicationId);

        return Inertia::render('Corporation/BusinessOperator/Management/Info', [
            'businessApplicationId' => $businessApplication->business_application_id,
            'businessName'      => is_null($businessApplication->businessOperator) ? $businessApplication->business_name : $businessApplication->businessOperator->business_name,
            'applicantName'     => $businessApplication->applicant_name,
            'applicantNameKana' => $businessApplication->applicant_name_kana,
            'zipCode'           => is_null($businessApplication->businessOperator) ? $businessApplication->zip_code : $businessApplication->businessOperator->zip_code,
            'pref'              => is_null($businessApplication->businessOperator) ?
                                    Prefecture::from($businessApplication->pref_code)->getName() :
                                    Prefecture::from($businessApplication->businessOperator->pref_code)->getName(),
            'city'              => is_null($businessApplication->businessOperator) ? $businessApplication->city : $businessApplication->businessOperator->city,
            'email'             => is_null($businessApplication->businessOperator) ? $businessApplication->email : $businessApplication->businessOperator->email,
            'phone'             => is_null($businessApplication->businessOperator) ? $businessApplication->phone : $businessApplication->businessOperator->phone,
        ]);
    }

    /**
     * 店舗管理：公開設定画面
     * @param int $businessId
     * @return Response
     */
    public function managementPublish(int $businessId): Response
    {
        $setting = $this->businessSettingService->publish($businessId);

        $businessOperator = $this->businessOperatorService->findByBusinessId($businessId);

        return Inertia::render('Corporation/BusinessOperator/Management/Publish', [
            'settingId' => $setting->setting_id,
            'isPublish' => $setting->is_publish,
            'businessApplicationId' => $businessOperator->business_application_id
        ]);
    }

    /**
     * 店舗管理：新規登録画面
     * @return Response
     */
    public function managementCreate(): Response
    {
        if (Session::has('businessCreateData')) {
            $data = Session::get('businessCreateData');
            Session::forget('businessCreateData');
        } else {
            $data = null;
        }

        return Inertia::render('Corporation/BusinessOperator/Management/Create', [
            'businessName'      => $data ? $data['businessName'] : '',
            'applicantName'     => $data ? $data['applicantName'] : '',
            'applicantNameKana' => $data ? $data['applicantNameKana'] : '',
            'zipCode'           => $data ? $data['zipCode'] : '',
            'prefCode'          => $data ? $data['prefCode'] : '',
            'city'              => $data ? $data['city'] : '',
            'streetAddress'     => $data ? $data['streetAddress'] : '',
            'building'          => $data ? $data['building'] : '',
            'email'             => $data ? $data['email'] : '',
            'phone'             => $data ? $data['phone'] : '',
        ]);
    }

    /**
     * 店舗管理：新規登録確認画面
     * @param CreateBusinessOperatorRequest $request
     * @return Response
     */
    public function managementConfirm(CreateBusinessOperatorRequest $request): Response
    {
        $input = $request->all();

        Session::put('businessCreateData', $input);

        return Inertia::render('Corporation/BusinessOperator/Management/Confirm', [
            'businessName'      => $request->businessName,
            'applicantName'     => $request->applicantName,
            'applicantNameKana' => $request->applicantNameKana,
            'zipCode'           => $request->zipCode,
            'prefCode'          => $request->prefCode,
            'pref'              => Prefecture::from($request->prefCode)->getName(),
            'city'              => $request->city,
            'streetAddress'     => $request->strertAddress,
            'email'             => $request->email,
            'password'          => $request->password,
            'passwordConfirmation'  => $request->password_confirmation,
            'phone'             => $request->phone,
        ]);
    }

    /**
     * 店舗管理：新規登録処理
     * @param Request $request
     * @return RedirectResponse
     */
    public function managementStore(Request $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $this->businessApplicationService->createBusinessOperatorApplication(Auth::guard('corporation')->user()->corporation_id, $request);
            });
            $this->flashMessageSuccess('関連店舗追加申請が完了しました');
            return Redirect::route('corporation.business-operator.management.index');
        } catch (\Exception $e) {
            $this->flashMessageFailed('予期せぬエラーが発生しました');
            return Redirect::route('corporation.business-operator.management.create');
        }
    }
}
