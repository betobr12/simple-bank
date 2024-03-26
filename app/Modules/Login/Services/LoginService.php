<?php

namespace App\Modules\Login\Services;

use App\Models\User;
use App\Libraries\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Modules\Login\Services\Contracts\LoginServiceInterface;

class LoginService implements LoginServiceInterface
{
    /**
     * @var mixed
     */
    private Document $document;

    public function __construct(
        Document $document
    ) {
        $this->document = $document;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handler($data)
    {
        return $this->loginUser($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    private function loginUser($data): JsonResponse
    {
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $data->cpf);

        $this->document->cpf_cnpj = $cpf_cnpj;

        if ($this->document->validateCPF() == false) {
            return response()->json(["error" => "CPF invalido"]);
        }

        $validator = Validator::make($data->all(), [
            'password' => ['required', 'string']
        ], [
            'password.required' => 'Senha obrigatória. '
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()]);
        }

        if (Auth::attempt(['cpf' => $cpf_cnpj, 'password' => $data->password])) {
            $user = auth()->user();
            $user->token = $user->createToken($cpf_cnpj)->accessToken;
            return response()->json(["success" => "Usuário logado com sucesso", "user" => $user]);
        }
        return response()->json(["error" => "Usuário ou senha invalido"]);
    }

}
