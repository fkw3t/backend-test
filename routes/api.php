<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

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

Route::post('/register/{provider}', [AuthController::class, 'register']);
Route::post('/login/{provider}', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::post('/transaction/transfer', [TransactionController::class, 'transfer']);
    Route::post('/logout/{provider}', [AuthController::class, 'logout']);
});
