<?php

namespace App\Http\Controllers;

use App\Card;
use App\Account;
use App\Transaction;
use App\CardTransaction;
use App\Libraries\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * @param Request $request
     */
    protected function deposit(Request $request)
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

        if (Transaction::create([
            'account_id' => $account->id,
            'user_id' => $user->id,
            'type_transaction_id' => 2,
            'value' => $request->value,
            'balance' => $balance->balance + $request->value,
            'document' => $request->document,
            'number_card' => null,
            'number_phone' => null,
            'description' => $request->description,
            'date' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["success" => "Deposito efetuado com sucesso"]);
        }
    }

    /**
     * @param Request $request
     */
    protected function cellRecharge(Request $request)
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

        if ($balance->balance < $request->value) {
            return response()->json(["error" => "Saldo insuficiente, por favor efetue um depósito"]);
        }

        $phone = new Phone();
        $phone->phone = $request->phone;

        if ($phone->phone() == false) {
            return response()->json(["error" => "Número do celular inválido"]);
        }

        if (Transaction::create([
            'account_id' => $account->id,
            'user_id' => $user->id,
            'type_transaction_id' => 5,
            'value' => -$request->value,
            'balance' => $balance->balance - $request->value,
            'document' => $request->document,
            'number_card' => null,
            'number_phone' => $request->phone,
            'description' => $request->description,
            'date' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["success" => "Recarga de celular efetuada com sucesso"]);
        }
    }

    /**
     * @param Request $request
     */
    protected function billPayment(Request $request)
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

        if ($balance->balance < $request->value) {
            return response()->json(["error" => "Saldo insuficiente, efetue um depósito"]);
        }

        if (Transaction::create([
            'account_id' => $account->id,
            'user_id' => $user->id,
            'type_transaction_id' => 1,
            'value' => -$request->value,
            'balance' => $balance->balance - $request->value,
            'document' => $request->document,
            'number_card' => null,
            'number_phone' => null,
            'description' => $request->description,
            'date' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["success" => "Pagamento de conta feito com sucesso"]);
        }
    }

    /**
     * @param Request $request
     */
    protected function transfer(Request $request)
    {
        if (!$user = Auth::user()) {
            return response()->json(["error" => "Usuário não foi autenticado"]);
        }

        if (!$account = Account::where('user_id', '=', $user->id)->first()) {
            return response()->json(["error" => "Usuário não possui uma conta"]);
        }

        if (!$account_favored = Account::where('cpf_cnpj', '=', $request->cpf_cnpj)->where('agency', '=', $request->agency)->where('number_account', '=', $request->number_account)->whereNull('deleted_at')->first()) {
            return response()->json(["error" => "Favorecido informado não possui uma conta"]);
        }

        $transaction = new Transaction();
        $transaction->account_id = $account->id;
        $transaction->start_date = \Carbon\Carbon::now();
        $balance = $transaction->getBalance();

        if ($balance->balance < $request->value) {
            return response()->json(["error" => "Saldo insuficiente, efetue um depósito"]);
        }

        if ($request->cpf_cnpj == $account->cpf_cnpj) {
            return response()->json(["error" => "Você não pode transferir dinheiro para o seu CPF/CNPJ"]);
        }

        if (Transaction::create([
            'account_id' => $account->id,
            'user_id' => $user->id,
            'type_transaction_id' => 4,
            'value' => -$request->value,
            'balance' => $balance->balance - $request->value,
            'document' => $request->document,
            'number_card' => null,
            'number_phone' => null,
            'description' => $request->description,
            'date' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now()
        ])) {

            $transaction_favored = new Transaction();
            $transaction_favored->account_id = $account_favored->id;
            $transaction_favored->start_date = \Carbon\Carbon::now();
            $balance_favored = $transaction_favored->getBalance();

            if (Transaction::create([
                'account_id' => $account_favored->id,
                'user_id' => $account_favored->user_id,
                'type_transaction_id' => 4,
                'value' => $request->value,
                'balance' => $balance_favored->balance + $request->value,
                'document' => $request->document,
                'number_card' => null,
                'number_phone' => null,
                'description' => "Credito transferência",
                'date' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now()
            ])) {
                return response()->json(["success" => "Transferência efetuada com sucesso"]);
            }
        }
        return response()->json(["error" => "Falha ao transferir o dinheiro"]);
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
