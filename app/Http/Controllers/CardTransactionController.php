<?php

namespace App\Http\Controllers;

use App\Account;
use App\Card;
use App\CardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardTransactionController extends Controller
{
    protected function new(Request $request)
    {
        if (!$user = Auth::user()) {
            return response()->json(array("error" => "Usuario não foi autenticado"));
        }

        if (!$account = Account::where('user_id','=',$user->id)->first()) {
            return response()->json(array("error" => "Usuario não possui uma conta"));
        }

        if (!$card = Card::where('user_id','=',$user->id)->where('account_id','=',$account->id)->first()) {
            return response()->json(array("error" => "Usuario não possui um cartão"));
        }

        $card_transaction               = new CardTransaction();
        $card_transaction->account_id   = $account->id;
        $card_transaction->start_date   = \Carbon\Carbon::now();
        $balance_card                   = $card_transaction->getBalanceCard();

        if ($balance_card->balance < $request->value) {
            return response()->json(array("error" => "Saldo insuficiente, efetue adicione dineiro no seu cartão para aumentar o limite"));
        }
        if (CardTransaction::create([
            'account_id'            => $account->id,
            'user_id'               => $user->id,
            'card_id'               => $card->id,
            'value'                 => -$request->value,
            'balance'               => $balance_card->balance - $request->value,
            'store'                 => $request->store,
            'description'           => $request->description,
            'date'                  => \Carbon\Carbon::now(),
            'created_at'            => \Carbon\Carbon::now(),
        ])){
            return response()->json(array("success" => "Pagamento de conta feito com sucesso"));
        }
    }
}
