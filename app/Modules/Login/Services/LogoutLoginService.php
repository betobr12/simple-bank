<?php

namespace App\Modules\Login\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Modules\Login\Services\Contracts\LogoutLoginServiceInterface;

class LogoutLoginService implements LogoutLoginServiceInterface
{

    /**
     * @return mixed
     */
    public function handler()
    {
        return $this->logoutUser();
    }

    public function logoutUser(): JsonResponse
    {
        $user = auth()->user();
        DB::table("oauth_access_tokens")->where('user_id', '=', $user->id)->delete();
        return response()->json([
            "success" => true,
            "message" => "logout executed successfully",
            'user' => $user,
            "status_code" => 200
        ]);
    }

}
