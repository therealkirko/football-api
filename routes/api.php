<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RewardController;

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


Route::post('/claim', [RewardController::class, 'store']);
Route::post('/customer', [CustomerController::class, 'store']);

Route::prefix('products')->group(function() {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/store', [ProductController::class, 'store']);
});

Route::prefix('analytica')->group(function() {
    Route::get('/', [AnalyticsController::class, 'index']);
    Route::prefix('rewards')->group(function() {
        Route::get('/{limit?}', [AnalyticsController::class, 'rewards']);
    });
    Route::get('/customers', [AnalyticsController::class, 'customers']);
});



