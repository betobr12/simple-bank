<?php
namespace App\Modules\Login\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface LogoutServiceInterface
{
    public function handler(): JsonResponse;

}
