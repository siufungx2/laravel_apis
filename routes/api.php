<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\FuelController;

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
Route::post('register', [LoginController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('me', [LoginController::class, 'userDetail']);
    Route::post('logout', [LoginController::class, 'logout']);

    // Holiday section
    Route::get('getpublicholiday', [HolidayController::class, 'getPublicHoliday']);
    Route::get('getschoolholiday', [HolidayController::class, 'getSchoolHoliday']);

    // Fuel list section
    Route::get('fuel', [FuelController::class, 'getFuelList']);
    Route::get('fuel/{search}', [FuelController::class, 'getFuelList']);
});

Route::fallback(function(){
    return response()->json(['message' => 'Page Not Found'], 404);
});
