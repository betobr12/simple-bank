<?php
namespace App\Modules\Account\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface AccountRegisterServiceInterface
{
    /**
     * @param $data
     */
    public function handler($data): JsonResponse;

}
