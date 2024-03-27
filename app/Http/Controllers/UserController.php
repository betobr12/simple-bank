<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Modules\User\Services\UserListService;
use App\Modules\User\Services\UserRegisterService;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @param UserRegisterService $userRegisterService
     * @return mixed
     */
    protected function register(Request $request, UserRegisterService $userRegisterService)
    {
        return $userRegisterService->handler($request);
    }

    /**
     * @param Request $request
     */
    protected function get(Request $request, UserListService $userList): JsonResponse
    {
        return $userList->handler($request);
    }
}
