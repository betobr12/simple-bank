<?php
namespace App\Modules\Account\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface AccountListServiceInterface
{

    /**
     * @param int $id
     */
    public function handler(int $id): JsonResponse;

}
