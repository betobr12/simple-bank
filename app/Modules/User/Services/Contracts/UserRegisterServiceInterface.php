<?php
namespace App\Modules\User\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface UserRegisterServiceInterface
{
    /**
     * @param $data
     */
    public function handler($data): JsonResponse;

}
