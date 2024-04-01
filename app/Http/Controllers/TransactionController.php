<?php

namespace App\Http\Controllers;

use App\Card;
use App\Account;
use App\Transaction;
use App\CardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Transaction\Services\DepositTransactionService;
use App\Modules\Transaction\Services\TransferTransactionService;
use App\Modules\Transaction\Services\BillPaymentTransactionService;
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
    protected function cardPay(Request $request)
    {
        if (!$user = Auth::user()) {
            return response()->json(["error" => "Usuário não foi autenticado"]);
        }

        if (!$account = Account::where('user_id', '=', $user->id)->first()) {
            return response()->json(["error" => "Usuário não possui uma conta"]);
        }

        $transaction = new Transaction();
        $transaction->account_id = $account->id;
        $transaction->start_date = \Carbon\Carbon::now();
        $balance = $transaction->getBalance();

        if (!$card = Card::where('user_id', '=', $user->id)->where('account_id', '=', $account->id)->first()) {
            return response()->json(["error" => "Usuário não possui um cartão"]);
        }

        if ($balance->balance < $request->value) {
            return response()->json(["error" => "Saldo insuficiente, efetue um depósito"]);
        }

        if (Transaction::create([
            'account_id' => $account->id,
            'user_id' => $user->id,
            'type_transaction_id' => 4,
            'value' => -$request->value,
            'balance' => $balance->balance - $request->value,
            'document' => $request->document,
            'number_card' => $card->number_card,
            'number_phone' => null,
            'description' => $request->description,
            'date' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now()
        ])) {
            $card_transaction = new CardTransaction();
            $card_transaction->account_id = $account->id;
            $card_transaction->start_date = \Carbon\Carbon::now();
            $balance_card = $card_transaction->getBalanceCard();

            if (CardTransaction::create([
                'account_id' => $account->id,
                'user_id' => $user->id,
                'card_id' => $card->id,
                'value' => $request->value,
                'balance' => $balance_card->balance + $request->value,
                'store' => "Pagamento do Cartão",
                'description' => $request->description,
                'date' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now()
            ])) {
                return response()->json(["success" => "Cartão de credito pago com sucesso"]);
            }
        }
    }
}
