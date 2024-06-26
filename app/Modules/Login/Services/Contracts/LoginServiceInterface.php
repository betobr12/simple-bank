<?php
namespace App\Modules\Login\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface LoginServiceInterface
{
    /**
     * @param $data
     */
    public function handler($data): JsonResponse;

}
