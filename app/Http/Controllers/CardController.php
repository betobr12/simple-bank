<?php

namespace App\Http\Controllers;

use App\Account;
use App\Card;
use App\CardTransaction;
use App\Libraries\GenerateCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    protected function newCard()
    {

        if (!$user = Auth::user()) {
            return response()->json(array("error" => "Usuario não foi autenticado"));
        }

        if (!$account = Account::where('user_id','=',$user->id)->first()) {
            return response()->json(array("error" => "Usuario não possui uma conta"));
        }

        if ($card_account = Card::where('user_id','=',$user->id)->where('account_id','=',$account->id)->whereNull('deleted_at')->first()) {
            return response()->json(array("error" => "Usuario já possui um cartão para sua conta"));
        }

        $card             = new GenerateCard();
        $card->account_id = $account->id;
        $card->user_id    = $user->id;
        $card->date       = \Carbon\Carbon::now();
        $card_number = $card->cardNumber();

        if ($card_data = Card::create([
            'user_id'       => $user->id,
            'account_id'    => $account->id,
            'number_card'   => $card_number->card_number,
            'created_at'    => \Carbon\Carbon::now()

        ])){
            if (CardTransaction::create([
                'account_id'    => $account->id,
                'user_id'       => $user->id,
                'card_id'       => $card_data->id,
                'value'         => 500,
                'balance'       => 500,
                'store'         => 'Credito Cadastro de Cartão',
                'date'          => \Carbon\Carbon::now(),
                'created_at'    => \Carbon\Carbon::now()

            ])){
                return response()->json(array("success" => "Cartão cadastrado com sucesso"));
            }
        }
        return response()->json(array("error" => "Occorreu alguma falha, tente acessar mais tarde"));

    }
}
