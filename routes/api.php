<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\StockController;

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

    Route::get('/products/{instoreId}', [CampaignController::class, 'getProducts']);

    Route::prefix('shift')->group(function() {
        Route::get('/check/status/{instoreId}', [ShiftController::class, 'checkStatus']);
        Route::get('/check/clockin/{instoreId}', [ShiftController::class, 'checkClockIn']);
        Route::get('/check/selfie/{instoreId}', [ShiftController::class, 'checkSelfie']);
        Route::get('/check/shelf/{instoreId}', [ShiftController::class, 'checkShelfPhoto']);
        Route::get('/check/stock/{instoreId}', [ShiftController::class, 'checkStockUpdate']);

        Route::post('/clockin', [ShiftController::class, 'index']);
        Route::post('/selfie', [FileController::class, 'storeInstoreFile']);
        Route::post('/stock', [ShiftController::class, 'stockTake']);
    });

    Route::prefix('stock')->group(function() {
        Route::post('/update', [StockController::class, 'update']);
    });

    Route::prefix('engagements')->group(function() {
        Route::get('/', [EngagementController::class, 'index']);
        Route::post('/create', [EngagementController::class, 'store']);
    });
});
