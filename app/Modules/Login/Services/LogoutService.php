<?php

namespace App\Modules\Login\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Modules\Login\Services\Contracts\LogoutServiceInterface;

class LogoutService implements LogoutServiceInterface
{

    /**
     * @return mixed
     */
    public function handler(): JsonResponse
    {
        return $this->logoutUser();
    }

    public function logoutUser(): JsonResponse
    {
        $user = auth()->user();

        dd($user);
        DB::table("oauth_access_tokens")->where('user_id', '=', $user->id)->delete();
        return response()->json([
            "success" => true,
            "message" => "Logout executed successfully",
            "status_code" => 200
        ]);
    }

}
