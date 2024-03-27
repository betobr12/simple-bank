<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Account\Services\AccountListService;
use App\Modules\Account\Services\AccountRegisterService;

class AccountController extends Controller
{
    protected function new (Request $request, AccountRegisterService $accountRegisterService)
    {
        return $accountRegisterService->handler($request);
    }

    /**
     * @param $id
     */
    protected function get($id, AccountListService $accountListService)
    {
        return $accountListService->handler($id);
    }
}
