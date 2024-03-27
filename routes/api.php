<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::post('/login', 'LoginController@login');

Route::group(['middleware' => 'auth:api'], function () {

    Route::prefix('account')->group(function ($id) {
        Route::post('/', 'AccountController@new');
        Route::get('/{id}', 'AccountController@get');
    });

    Route::prefix('card')->group(function () {
        Route::post('/new_card', 'CardController@newCard');

        Route::prefix('card_transaction')->group(function () {
            Route::post('/', 'CardTransactionController@new');
        });
    });

    Route::post('logout', 'LoginController@logout');

    Route::prefix('transaction')->group(function ($id) {
        Route::post('/deposit', 'TransactionController@deposit');
        Route::post('/cell_recharge', 'TransactionController@cellRecharge');
        Route::post('/bill_payment', 'TransactionController@billPayment');
        Route::post('/transfer', 'TransactionController@transfer');
        Route::post('/card_pay', 'TransactionController@cardPay');
    });

    Route::prefix('users')->group(function ($id) {
        Route::get('/', 'UserController@get');
        Route::post('/register', 'UserController@register');
    });

});
