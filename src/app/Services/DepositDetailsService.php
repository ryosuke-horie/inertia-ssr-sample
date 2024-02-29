<?php

namespace App\Services;

use App\Enums\EntityType;
use App\Repositories\TransferRequest\TransferRequestRepositoryInterface;
use App\Repositories\TotalTip\TotalTipRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DepositDetailsService
{
    private TransferRequestRepositoryInterface $transferRequestRepositoryInterface;
    private TotalTipRepositoryInterface $totalTipRepositoryInterface;
    private StaffRepositoryInterface $staffRepositoryInterface;
    private BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface;

    public function __construct(
        TransferRequestRepositoryInterface $transferRequestRepositoryInterface,
        TotalTipRepositoryInterface $totalTipRepositoryInterface,
        StaffRepositoryInterface $staffRepositoryInterface,
        BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface
    ) {
        $this->transferRequestRepositoryInterface = $transferRequestRepositoryInterface;
        $this->totalTipRepositoryInterface = $totalTipRepositoryInterface;
        $this->staffRepositoryInterface = $staffRepositoryInterface;
        $this->businessOperatorRepositoryInterface = $businessOperatorRepositoryInterface;
    }

    /**
     * 売上入金明細一覧用データ取得
     * @param EntityType $entityType
     * @param int $entityId
     * @return Collection
     */
    public function getDepositDetails(EntityType $entityType, int $entityId): Collection
    {
        $args = [];

        $transferRequests = $this->transferRequestRepositoryInterface->getTransferRequestByEntityDesc($entityType, $entityId);

        $args = $transferRequests->map(function ($transferRequest) {
            $fromFormat = Carbon::parse($transferRequest->transaction_period_from)->format('Y年n月');
            $toFormat = Carbon::parse($transferRequest->transaction_period_to)->format('Y年n月');
            return [
                'requestId'                 => $transferRequest->request_id,
                'transferAmount'            => number_format($transferRequest->transfer_amount),
                'transactionPeriodFrom'     => $fromFormat,
                'transactionPeriodTo'       => $transferRequest->transaction_period_from == $transferRequest->transaction_period_to ? null : $toFormat
            ];
        });

        return $args;
    }

    /**
     * 売上入金明細スタッフ用データ取得
     * @param int $requestId
     */
    public function getDepositDetailsStaff(int $requestId)
    {
        $args = [];

        // 対象売上入金明細取得
        $transferRequest = $this->transferRequestRepositoryInterface->getTransferRequestByRequestId($requestId);

        // 売上
        $args['transferRequestAmount'] = number_format($transferRequest->transfer_request_amount);

        // 振込金額
        $args['transferAmount'] = number_format($transferRequest->transfer_amount);

        // 利用料
        $args['usageFeeAmount'] = number_format($transferRequest->transfer_request_amount * ($transferRequest->usage_fee_rate / 100));

        // 決済手数料
        $args['paymentFeeAmount'] = number_format($transferRequest->transfer_request_amount * ($transferRequest->payment_fee_rate / 100));

        // 振込手数料
        $args['transferFeeAmount'] = number_format($transferRequest->transfer_fee_amount);

        $from = Carbon::parse($transferRequest->transaction_period_from);
        $args['transactionPeriodFrom'] = $from->format('Y年n月');

        $to = Carbon::parse($transferRequest->transaction_period_to);
        $args['transactionPeriodTo'] = $to->format('Y年n月');

        $staffList = $this->totalTipRepositoryInterface->getStaffPointByBusinessPeriod($transferRequest->entity_id, $from->format('Y-m'), $to->format('Y-m'));

        $args['staffList'] = $staffList->map(function ($staff) use ($transferRequest) {
            $allocateRate = round(($staff->total_amount / $transferRequest->transfer_request_amount) * 100);
            return [
                'staffName' => $staff->staff_name,
                'allocateAmount' => number_format(round(($allocateRate / 100) * $transferRequest->transfer_amount)),
                'src' => $this->staffRepositoryInterface->findStaffProfileImageByStaffIdOrder($staff->entity_id, 1)->file_name ?? ''
            ];
        });

        return $args;
    }

    /**
     * 法人：売上入金明細事業者一覧用データ取得
     * @param int $requestId
     */
    public function getCorporationDepositDetailsBusiness(int $requestId)
    {
        $args = [];

        $args['requestId'] = $requestId;

        // 対象売上入金明細取得
        $transferRequest = $this->transferRequestRepositoryInterface->getTransferRequestByRequestId($requestId);

        // 売上
        $args['transferRequestAmount'] = number_format($transferRequest->transfer_request_amount);

        // 振込金額
        $args['transferAmount'] = number_format($transferRequest->transfer_amount);

        // 利用料
        $args['usageFeeAmount'] = number_format($transferRequest->transfer_request_amount * ($transferRequest->usage_fee_rate / 100));

        // 決済手数料
        $args['paymentFeeAmount'] = number_format($transferRequest->transfer_request_amount * ($transferRequest->payment_fee_rate / 100));

        // 振込手数料
        $args['transferFeeAmount'] = number_format($transferRequest->transfer_fee_amount);

        $from = Carbon::parse($transferRequest->transaction_period_from);
        $args['transactionPeriodFrom'] = $from->format('Y年n月');

        $to = Carbon::parse($transferRequest->transaction_period_to);
        $args['transactionPeriodTo'] = $to->format('Y年n月');

        $businessOperators = $this->totalTipRepositoryInterface->getBusinessByCorporationIdPeriod($transferRequest->entity_id, $from->format('Y-m'), $to->format('Y-m'));

        $args['businessOperators'] = $businessOperators->map(function ($businessOperator) use ($transferRequest) {
            $allocateRate = round(($businessOperator->total_amount / $transferRequest->transfer_request_amount) * 100);
            return [
                'businessId'    => $businessOperator->business_id,
                'businessName' => $businessOperator->business_name,
                'allocateAmount' => number_format(round(($allocateRate / 100) * $transferRequest->transfer_amount)),
                'src' => $this->businessOperatorRepositoryInterface->findBusinessProfileImageByBusinessIdOrder($businessOperator->business_id, 1)->file_name ?? ''
            ];
        });

        return $args;
    }

    /**
     * 法人：売上入金明細スタッフ用データ取得
     * @param int $requestId
     * @param int $businessId
     */
    public function getCorporationDepositDetailsStaff(int $requestId, int $businessId)
    {
        $args = [];

        $args['requestId']  = $requestId;
        $args['businessName'] = $this->businessOperatorRepositoryInterface->findByBusinessId($businessId)->business_name;

        // 対象売上入金明細取得
        $transferRequest = $this->transferRequestRepositoryInterface->getTransferRequestByRequestId($requestId);

        $from = Carbon::parse($transferRequest->transaction_period_from);
        $args['transactionPeriodFrom'] = $from->format('Y年n月');

        $to = Carbon::parse($transferRequest->transaction_period_to);
        $args['transactionPeriodTo'] = $to->format('Y年n月');

        // 事業者の売上取得
        $businessTotalAmount = $this->totalTipRepositoryInterface->getTotalAmountByBusinessPeriod($businessId, $from->format('Y-m'), $to->format('Y-m'));

        $businessAllocateRate = round(($businessTotalAmount / $transferRequest->transfer_request_amount) * 100);
        $businessAllocateAmount = round(($businessAllocateRate / 100) * $transferRequest->transfer_amount);
        $args['allocateAmount'] =  number_format($businessAllocateAmount);

        $staffList = $this->totalTipRepositoryInterface->getStaffPointByBusinessPeriod($businessId, $from->format('Y-m'), $to->format('Y-m'));

        $args['staffList'] = $staffList->map(function ($staff) use ($businessTotalAmount, $businessAllocateAmount) {
            $staffAllocateRate = round(($staff->total_amount / $businessTotalAmount) * 100);
            return [
                'staffName' => $staff->staff_name,
                'allocateAmount' => number_format(round(($staffAllocateRate / 100) * $businessAllocateAmount)),
                'src' => $this->staffRepositoryInterface->findStaffProfileImageByStaffIdOrder($staff->entity_id, 1)->file_name ?? ''
            ];
        });

        return $args;
    }
}
