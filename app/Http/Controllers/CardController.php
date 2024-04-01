<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Modules\Card\Services\CardService;

class CardController extends Controller
{
    /**
     * @param CardService $cardService
     * @return mixed
     */
    protected function newCard(
        Request $request,
        CardService $cardService
    ) {
        return $cardService->handler($request);
    }
}
