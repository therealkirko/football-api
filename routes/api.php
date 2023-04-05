<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ShiftController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function() {
    Route::prefix('verify')->group(function() {
        Route::post('/phone', [AuthController::class, 'phoneVerify']);
    });
    
    Route::post('/user/password', [AuthController::class, 'setPin']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/campaign/instore', [CampaignController::class, 'instore']);
    Route::get('/campaign/instore/{id]', [CampaignController::class, 'showInstore']);

    Route::prefix('shift')->group(function() {
        Route::post('/clockin', [ShiftController::class, 'index']);
        Route::post('/stock-take', [ShiftController::class, 'stockTake']);
    });
});
