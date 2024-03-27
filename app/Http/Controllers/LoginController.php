<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Login\Services\LoginService;
use App\Modules\Login\Services\LogoutService;

class LoginController extends Controller
{

    /**
     * @param Request $request
     * @param LoginService $loginService
     * @return mixed
     */
    protected function login(Request $request, LoginService $loginService)
    {
        return $loginService->handler($request);
    }

    /**
     * @param Request $request
     * @param LoginService $loginService
     * @return mixed
     */
    protected function logout(Request $request, LogoutService $loginService)
    {
        return $loginService->handler($request);
    }
}
