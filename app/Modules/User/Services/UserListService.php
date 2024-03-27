<?php

namespace App\Modules\User\Services;

use PHPUnit\Util\Json;
use App\Libraries\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Modules\User\Services\Contracts\UserListServiceInterface;
use App\Modules\User\Repositories\Contracts\UserRepositoryInterface;

class UserListService implements UserListServiceInterface
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
    public function handler($data): JsonResponse
    {
        return $this->list($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function list(Request $data): JsonResponse
    {

        $user = (object) [];
        $user->name = $data->name;
        $user->cpf = $data->cpf;
        return response()->json([
            "success" => "Usuário registrado com sucesso",
            "user" => $this->userRepository->getUser($user)]
        );

    }
}
