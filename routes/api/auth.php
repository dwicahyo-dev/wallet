<?php

use App\Http\Controllers\Api\Auth\AuthenticatedTokenController;
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
    'as' => 'auth.',
    'prefix' => 'auth',
], function () {
    Route::post('login', [AuthenticatedTokenController::class, 'store'])
        ->middleware('guest');

    Route::post('logout', [AuthenticatedTokenController::class, 'destroy'])
        ->middleware('auth:sanctum');
});
