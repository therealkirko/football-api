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
    Route::prefix('campaigns')->group(function() {
        Route::get('/instore', [CampaignController::class, 'instore']);
        Route::get('/instore/{id}', [CampaignController::class, 'showInstore']);
    });

    Route::prefix('shift')->group(function() {
        Route::get('/check/clockin/{uuid}', [ShiftController::class, 'checkClockIn']);
        Route::get('/check/selfie/{uuid}', [ShiftController::class, 'checkSelfie']);
        Route::get('/check/shelf/{uuid}', [ShiftController::class, 'checkShelfPhoto']);
        Route::get('/check/stock/{uuid}', [ShiftController::class, 'checkStockUpdate']);

        Route::post('/clockin', [ShiftController::class, 'index']);
        Route::post('/stock-take', [ShiftController::class, 'stockTake']);
    });
});
