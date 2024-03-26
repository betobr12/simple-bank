<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Login\Services\LoginService;
use App\Modules\User\Services\UserRegisterService;

class UserController extends Controller
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
     */
    protected function register(Request $request, UserRegisterService $userRegisterService)
    {
        return $userRegisterService->handler($request);
    }

    /**
     * @param Request $request
     */
    protected function get(Request $request)
    {
        if (!$user = Auth::user()) {
            return response()->json(["error" => "Usuário não foi autenticado"]);
        }
        $user_obj = new User();
        $user_obj->name = $request->name;
        $user_obj->cpf = $request->cpf;
        return response()->json($user_obj->get());
    }
}
