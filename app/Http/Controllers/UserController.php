<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    protected function login(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string'],
        ],[
            'email.required'        => 'Email obrigatorio. ',
            'email.max'             => 'Caractere maximo para o email foi ultrapassado.',
            'email.email'           => 'Email invalido. ',
            'password.required'     => 'Senha obrigatoria. ',
           ]
        );

        if ($validator->fails()) {
            return response()->json(array("error"=>$validator->errors()));
        } else {
            if (Auth::attempt(['email' => $data['email'],'password' => $data['password']])) {
                $user = auth()->user();
                $user->token = $user->createToken($data['email'])->accessToken;

                return response()->json(array("success"=>"Usuario logado com sucesso","user"=>$user));
            } else {
                return response()->json(array("error"=>"Usuario ou senha invalido"));
            }
        }
    }
}
