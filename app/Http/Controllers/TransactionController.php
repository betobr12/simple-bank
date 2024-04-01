<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use App\Modules\Transaction\Services\DepositTransactionService;
use App\Modules\Transaction\Services\TransferTransactionService;
use App\Modules\Transaction\Services\BillPaymentTransactionService;
use App\Modules\Transaction\Services\CardPaymentTransactionService;
use App\Modules\Transaction\Services\CellRechargeTransactionService;

class TransactionController extends Controller
{
    /**
     * @param Request $request
     */
    protected function deposit(
        Request $request,
        DepositTransactionService $depositTransactionService
    ) {
        return $depositTransactionService->handler($request);
    }

    /**
     * @param Request $request
     */
    protected function cellRecharge(
        Request $request,
        CellRechargeTransactionService $cellRechargeTransactionService
    ) {
        return $cellRechargeTransactionService->handler($request);
    }

    /**
     * @param Request $request
     */
    protected function billPayment(Request $request, BillPaymentTransactionService $billPaymentTransactionService)
    {
        return $billPaymentTransactionService->handler($request);
    }

    /**
     * @param Request $request
     * @param TransferTransactionService $transferTransactionService
     * @return mixed
     */
    protected function transfer(
        Request $request,
        TransferTransactionService $transferTransactionService
    ) {
        return $transferTransactionService->handler($request);
    }

    /**
     * @param Request $request
     */
    protected function cardPay(
        Request $request,
        CardPaymentTransactionService $cardPaymentTransactionService
    ) {
        return $cardPaymentTransactionService->handler($request);
    }
}
