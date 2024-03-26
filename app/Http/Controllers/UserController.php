<?php

namespace App\Http\Controllers;

use App\User;
use App\Libraries\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @param Request $request
     */
    protected function login(Request $request)
    {
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $request->cpf);

        $document = new Document;
        $document->cpf_cnpj = $cpf_cnpj;

        if ($document->validateCPF() == false) {
            return response()->json(["error" => "CPF invalido"]);
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string']
        ], [
            'password.required' => 'Senha obrigatória. '
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()]);
        }

        if (Auth::attempt(['cpf' => $cpf_cnpj, 'password' => $request->password])) {
            $user = auth()->user();
            $user->token = $user->createToken($cpf_cnpj)->accessToken;
            return response()->json(["success" => "Usuário logado com sucesso", "user" => $user]);
        }
        return response()->json(["error" => "Usuário ou senha invalido"]);
    }

    /**
     * @param Request $request
     */
    protected function register(Request $request)
    {
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $request->cpf);

        $document = new Document;
        $document->cpf_cnpj = $cpf_cnpj;

        if ($document->validateCPF() == false) {
            return response()->json(["error" => "CPF invalido"]);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:11'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4']
        ], [
            'name.required' => 'Nome do usuário obrigatorio',
            'name.max' => 'Caractere maximo para o nome foi ultrapassado',
            'phone.required' => 'Telefone é obrigatorio',
            'phone.max' => 'Caractere maximo para o telefone foi ultrapassado',
            'email.required' => 'Email obrigatorio',
            'email.unique' => 'Esse email foi cadastrado para outro usuário',
            'email.max' => 'Caractere maximo para o email foi ultrapassado',
            'email.email' => 'Email invalido',
            'password.required' => 'Senha obrigatória',
            'password.min' => 'É necessario mais caracteres para senha'
        ]
        );

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()]);
        }

        if (User::where('email', '=', $request->email)->first()) {
            return response()->json(["error" => "Esse email foi cadastrado para outro usuário"]);
        }

        if (User::where('cpf', '=', $cpf_cnpj)->first()) {
            return response()->json(["error" => "Esse cpf foi cadastrado para outro usuário"]);
        }

        if ($user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $cpf_cnpj,
            'phone' => $request->phone,
            'password' => Hash::make($request->password)
        ])) {
            $user->save();
            $user->token = $user->createToken($request->email)->accessToken;
            return response()->json(["success" => "Usuário registrado com sucesso", "user" => $user]);
        }
        return response()->json(["error" => "Erro ao registrar o usuário"]);
    }

    /**
     * @param Request $request
     */
    protected function get(Request $request)
    {
        if (!$user = Auth::user()) {
            return response()->json(["error" => "Usuário não foi autenticado"]);
        }
        $user_obj = new User();
        $user_obj->name = $request->name;
        $user_obj->cpf = $request->cpf;
        return response()->json($user_obj->get());
    }
}
