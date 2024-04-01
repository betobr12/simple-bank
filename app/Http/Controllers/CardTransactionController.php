<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\CardTransaction\Services\CardTransactionService;

class CardTransactionController extends Controller
{
    protected function new (
        Request $request,
        CardTransactionService $cardTransactionService
    ) {
        return $cardTransactionService->handler($request);
    }
}
