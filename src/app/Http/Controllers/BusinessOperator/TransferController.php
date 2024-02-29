<?php

namespace App\Http\Controllers\BusinessOperator;

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
use Illuminate\Http\JsonResponse;

class TransferController extends Controller
{
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
        return Inertia::render('BusinessOperator/Transfer/Select');
    }

    /**
     * 振込申請画面表示
     * @return Response
     */
    public function application(): Response
    {
        $args = $this->transferService->getTranserApplication(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Transfer/Application', [
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
            $this->transferService->updateTransferApplication($settingId, $request->isAutoApply, $request->autoApplyAmount);

            return Redirect::route('business-operator.transfer.application')->with('success', '振込申請設定を更新しました');
        } catch (\Exception $e) {
            return Redirect::route('business-operator.transfer.application')->with('fail', '振込申請設定の更新に失敗しました');
        }
    }

    /**
     * 口座情報画面表示
     * @return Response
     */
    public function bankAccount(): Response
    {
        $bankAccount = $this->transferService->getBankAccount(EntityType::BusinessOperator, Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Transfer/BankAccount', [
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

            $this->transferService->updateAccountBank(EntityType::BusinessOperator, Auth::guard('business-operator')->user()->business_id, $param);

            return Redirect::route('business-operator.transfer.bank-account')->with('success', '口座情報を更新しました。');
        } catch (\Exception $e) {
            return Redirect::route('business-operator.transfer.bank-account')->with('fail', '口座情報の更新に失敗しました。');
        }
    }

    /**
     * 支払通知書画面表示
     * @return Response
     */
    public function paymentAdvice()
    {
        $args = $this->transferService->getPaymentAdvice(EntityType::BusinessOperator, Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Transfer/PaymentAdvice', ['transferRequests' => $args]);
    }

    /**
     * API:支払通知書PDF生成
     * @return JsonResponse
     */
    public function paymentAdvicePdf(Request $request): JsonResponse
    {
        $args = $this->transferService->paymentAdvicePdf($request->requestId);

        return response()->json([
            'filePath'  => $args['filePath'],
            'fileName'  => $args['fileName']
        ]);
    }
}
