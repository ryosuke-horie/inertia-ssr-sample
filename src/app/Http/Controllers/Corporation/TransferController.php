<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\EntityType;
use Illuminate\Support\Facades\Redirect;
use App\Services\TransferService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\BusinessOperator\Transfer\UpdateApplicationRequest;
use App\Http\Requests\BusinessOperator\Transfer\UpdateBankAccountRequest;
use App\Trais\FlashMessageTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class TransferController extends Controller
{
    use FlashMessageTrait;

    private TransferService $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * 選択画面表示
     * @return Response
     */
    public function select(): Response
    {
        return Inertia::render('Corporation/Transfer/Select');
    }

    /**
     * 振込申請画面表示
     * @return Response
     */
    public function application(): Response
    {
        $args = $this->transferService->getCorporationTranserApplication(Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/Transfer/Application', [
            'settingId'         => $args['settingId'],
            'isAutoApply'       => $args['isAutoApply'],
            'autoApplyAmount'   => $args['autoApplyAmount'],
        ]);
    }

    /**
     * 振込申請設定更新
     * @param UpdateApplicationRequest $request
     * @param int $settingId
     * @return RedirectResponse
     */
    public function updateApplication(UpdateApplicationRequest $request, int $settingId): RedirectResponse
    {
        try {
            $this->transferService->updateCorporationTransferApplication($settingId, $request->isAutoApply, $request->autoApplyAmount);

            $this->flashMessageSuccess('更新しました');
            return Redirect::route('corporation.transfer.application');
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('corporation.transfer.application');
        }
    }

    /**
     * 口座情報画面表示
     * @return Response
     */
    public function bankAccount(): Response
    {
        $bankAccount = $this->transferService->getBankAccount(EntityType::Corporation, Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/Transfer/BankAccount', [
            'bankName'              => is_null($bankAccount) ? null : decrypt($bankAccount->bank_name),
            'accountType'          => is_null($bankAccount) ? null : decrypt($bankAccount->account_type),
            'branchName'           => is_null($bankAccount) ? null : decrypt($bankAccount->branch_name),
            'accountNumber'        => is_null($bankAccount) ? null : decrypt($bankAccount->account_number),
            'accountHolderName'   => is_null($bankAccount) ? null : decrypt($bankAccount->account_holder_name),
        ]);
    }

    /**
     * 口座情報更新
     * @param UpdateBankAccountRequest $request
     * @return RedirectResponse
     */
    public function bankAccountUpdate(UpdateBankAccountRequest $request): RedirectResponse
    {
        try {
            $param = [
                'bank_name'             => encrypt($request->bankName),
                'account_type'          => encrypt($request->accountType),
                'branch_name'           => encrypt($request->branchName),
                'account_number'        => encrypt($request->accountNumber),
                'account_holder_name'   => encrypt($request->accountHolderName),
            ];

            $this->transferService->updateAccountBank(EntityType::Corporation, Auth::guard('corporation')->user()->corporation_id, $param);

            $this->flashMessageSuccess('更新しました');
            return Redirect::route('corporation.transfer.bank-account');
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('corporation.transfer.bank-account');
        }
    }

    /**
     * 支払通知書画面表示
     * @return Response
     */
    public function paymentAdvice()
    {
        $args = $this->transferService->getPaymentAdvice(EntityType::Corporation, Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/Transfer/PaymentAdvice', ['transferRequests' => $args]);
    }

    /**
     * API:支払通知書PDF生成
     * @return JsonResponse
     */
    public function paymentAdvicePdf(Request $request): JsonResponse
    {
        $args = $this->transferService->corporationPaymentAdvicePdf($request->requestId);

        return response()->json([
            'filePath'  => $args['filePath'],
            'fileName'  => $args['fileName']
        ]);
    }
}
