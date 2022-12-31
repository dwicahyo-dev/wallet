<?php

use App\Http\Requests\Wallet\StoreDepositRequest;
use App\Http\Requests\Wallet\StoreTransferRequest;
use App\Http\Requests\Wallet\StoreWithdrawRequest;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Http\Request;
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

Route::group([
    'middleware' => ['auth:sanctum'],
    'as' => 'wallet.',
    'prefix' => 'wallet',
], function () {

    /**
     * Get Balance
     */
    Route::get('balance', function (Request $request) {
        return response()->json([
            'status' => true,
            'data' => [
                'user' => $request->user()->load(['wallet'])
            ]
        ], 200);
    });

    /**
     * Deposit
     */
    Route::post('deposit', function (StoreDepositRequest $request) {
        $deposit = $request->user()->deposit($request->amount, [
            'title' => 'Deposit'
        ], true);

        return response()->json([
            'status' => true,
            'data' => [
                'deposit' => $deposit,
                'balance' => $request->user()->balance
            ]
        ], 201);
    });

    /**
     * Transfer Balance
     */
    Route::post('transfer', function (StoreTransferRequest $request) {
        try {
            $amount = $request->amount;
            $destination = User::find(2);

            $request->user()->transfer($destination, $amount);

            return response()->json([
                'status' => true,
                'data' => [
                    'origin' => [
                        'balance' => $request->user()->balance,
                    ],
                    'destination' => [
                        'balance' => $destination->balance
                    ]
                ]
            ], 201);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 403);
        }
    });

    /**
     * Withdraw
     */
    Route::post('withdraw', function (StoreWithdrawRequest $request) {
        try {
            $request->user()->withdraw($request->amount);

            return response()->json([
                'status' => true,
                'data' => [
                    'user' => $request->user()->load(['wallet'])
                ]
            ], 201);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 403);
        }
    });

    Route::post('deposit/{transaction}/confirm', function (Transaction $transaction) {
        return $transaction;
    });
});
