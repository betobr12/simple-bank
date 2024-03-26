<?php

namespace App\Modules\User\Services;

use App\Libraries\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Modules\User\Repositories\Contracts\UserRepositoryInterface;
use App\Modules\User\Services\Contracts\UserRegisterServiceInterface;

class UserRegisterService implements UserRegisterServiceInterface
{
    private Document $document;
    private UserRepositoryInterface $userRepository;

    /**
     * @param Document $document
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        Document $document,
        UserRepositoryInterface $userRepository
    ) {
        $this->document = $document;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handler($data)
    {
        return $this->userRegister($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function userRegister($data): JsonResponse
    {
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $data->cpf);

        $this->document->cpf_cnpj = $cpf_cnpj;

        if ($this->document->validateCPF() == false) {
            return response()->json(["error" => "CPF inválido"]);
        }

        $validator = Validator::make($data->all(), [
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
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()]);
        }

        $dataValidate = (object) [];
        $dataValidate->userValidation = [
            'cpf' => $cpf_cnpj,
            'email' => $data->email
        ];

        if (count($this->userRepository->getUser($dataValidate)) > 0) {
            return response()->json(["error" => "Esse usuario foi cadastrado no sistema"]);
        }

        if ($user = $this->userRepository->create([
            'name' => $data->name,
            'email' => $data->email,
            'cpf' => $cpf_cnpj,
            'phone' => $data->phone,
            'password' => Hash::make($data->password)
        ])) {
            return response()->json(["success" => "Usuário registrado com sucesso", "user" => $user]);
        }
        return response()->json(["error" => "Erro ao registrar o usuário"]);
    }
}
