<?php

namespace App\Http\Controllers;

use App\Account;
use App\Company;
use App\Libraries\Document;
use App\Libraries\GenerateAccount;
use App\Person;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected function new(Request $request)
    {
        if (!$user = Auth::user()) {
            return response()->json(array("error" => "Usuario não foi autenticado"));
        }

        $document           = new Document();
        $cpf_cnpj           = preg_replace( '/[^0-9]/', '', $request->cpf_cnpj);
        $document->cpf_cnpj = $cpf_cnpj;

        if (strlen($cpf_cnpj) == 11) {
            $type = 2;
            if( !$document->validateCPF($cpf_cnpj) ){
                return response()->json(['error'=>'CPF/CNPJ inválido']);
            }
        } else if (strlen($cpf_cnpj) == 14) {
            $type = 1;
            if( !$document->validateCNPJ($cpf_cnpj) ){
                return response()->json(['error'=>'CPF/CNPJ inválido']);
            }
        }else{
            return response()->json(['error'=>'CPF/CNPJ inválido']);
        }

        if (Account::where('cpf_cnpj','=',$cpf_cnpj)->first()) {
            return response()->json(['error'=>'CPF/CNPJ Cadastrado para outra conta']);
        }

        if (!$account = Account::where('user_id','=',$user->id)->first()) {

            $generate_account             = new GenerateAccount();
            $generate_account->cpf_cnpj   = $cpf_cnpj;
            $generate_account->id         = $user->id;
            $generate_account->date       = $user->created_at;
            $account_data                 = $generate_account->accountNumber();

            if ($account = Account::create([
                'user_id'           => $user->id,
                'cpf_cnpj'          => $request->cpf_cnpj,
                'agency'            => 1,
                'type_id'           => $type,
                'number_account'    => $account_data->account_number,
                'digit'             => $account_data->digit,
                'created_at'        => \Carbon\Carbon::now(),
            ])){
                if ($account->type_id == 1) {
                    if (Company::create([
                        'account_id'    => $account->id,
                        'user_id'       => $account->user_id,
                        'social_reason' => $request->social_reason,
                        'fantasy_name'  => $request->fantasy_name,
                        'created_at'        => \Carbon\Carbon::now()
                    ])){
                        return response()->json(array("success" => "Conta tipo Company criada com sucesso", "data" => $account->get()));
                    }
                } else if ($account->type_id == 2) {
                    if (Person::create([
                        'account_id'    => $account->id,
                        'user_id'       => $account->user_id,
                        'name'          => $request->name,
                        'created_at'    => \Carbon\Carbon::now()
                    ])){
                        return response()->json(array("success" => "Conta tipo Person criada com sucesso", "data" => $account->get()));
                    }
                }
            }
        } else {
            return response()->json(array("error" => "Você já possui uma conta", "data" => $account->get()));
        }
    }

    protected function get($id) {

        if (!$user = Auth::user()) {
            return response()->json(array("error" => "Usuario não foi autenticado"));
        }

        if (!Account::where('id','=',$id)->where('user_id','=',$user->id)->first()) {
            return response()->json(array("error" => "ID informado não pertence a sua conta"));
        }

        $account                = new Account();
        $account->account_id    = $id;

        $transactions                = new Transaction();
        $transactions->account_id    = $id;

        return response()->json(array("data_account" => $account->get(), "transactions" => $transactions->getTransactions()));

    }
}
