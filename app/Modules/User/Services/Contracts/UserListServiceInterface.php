<?php
namespace App\Modules\User\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface UserListServiceInterface
{
    /**
     * @param $data
     */
    public function handler($data): JsonResponse;

}
