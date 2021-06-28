<?php

namespace App\Http\Controllers;

use App\Libraries\Control;
use App\Libraries\Document;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    protected function login(Request $request)
    {
        $data = $request->all();

        $cpf_cnpj = preg_replace( '/[^0-9]/', '', $data['cpf']);

        $document           = new Document;
        $document->cpf_cnpj = $cpf_cnpj;

        if ($document->validateCPF() == false) {
            return response()->json(array("error"=>"CPF invalido"));
        }

        $validator = Validator::make($data,[
            'password' => ['required', 'string'],
        ],[
            'password.required'     => 'Senha obrigatoria. ',
        ]);

        if ($validator->fails()) {
            return response()->json(array("error"=>$validator->errors()));
        } else {
            if (Auth::attempt(['cpf' => $cpf_cnpj,'password' => $data['password']])) {
                $user = auth()->user();
                $user->token = $user->createToken($cpf_cnpj)->accessToken;

                return response()->json(array("success"=>"Usuario logado com sucesso","user"=>$user));
            } else {
                return response()->json(array("error"=>"Usuario ou senha invalido"));
            }
        }
    }

    protected function register(Request $request)
    {
        $data = $request->all();

        $cpf_cnpj = preg_replace( '/[^0-9]/', '', $data['cpf']);

        $document           = new Document;
        $document->cpf_cnpj = $cpf_cnpj;

        if ($document->validateCPF() == false) {
            return response()->json(array("error"=>"CPF invalido"));
        }

        $validator = Validator::make($data, [
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['required', 'string', 'max:11'],
            'email'     => ['required', 'string', 'email', 'max:255','unique:users'],
            'password'  => ['required', 'string', 'min:4'],
        ],[
            'name.required'         => 'Nome do usuario obrigatorio',
            'name.max'              => 'Caractere maximo para o nome foi ultrapassado',
            'phone.required'        => 'Telefone é obrigatorio',
            'phone.max'             => 'Caractere maximo para o telefone foi ultrapassado',
            'email.required'        => 'Email obrigatorio',
            'email.unique'          => 'Esse email foi cadastrado para outro usuario',
            'email.max'             => 'Caractere maximo para o email foi ultrapassado',
            'email.email'           => 'Email invalido',
            'password.required'     => 'Senha obrigatoria',
            'password.min'          => 'É necessario mais caracteres para senha',
           ]
        );

        if ($validator->fails()) {
            return response()->json(array("error"=>$validator->errors()));
        } else {

            if (User::where('email','=',$request->email)->first()) {
                return response()->json(array("error"=>"Esse email foi cadastrado para outro usuario"));
            }

            if (User::where('cpf','=',$cpf_cnpj)->first()) {
                return response()->json(array("error"=>"Esse cpf foi cadastrado para outro usuario"));
            }

            if ($user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'cpf'       => $cpf_cnpj,
                'phone'     => $data['phone'],
                'password'  => Hash::make($data['password']),
            ])) {
                $user->save();
                $user->token = $user->createToken($request->email)->accessToken;
                return response()->json(array("success"=>"Usuario registrado com sucesso","user"=>$user));
            } else {
                return response()->json(array("error"=>"Erro ao registrar o usuario"));
            }
        }
    }
}
