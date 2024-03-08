<?php

use App\Http\Controllers\Api\V1\HolidayPlanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('V1')->group(function (){
    Route::get('/holiday-plans', [HolidayPlanController::class, 'index'] );
    Route::get('/holiday-plans/{id}', [HolidayPlanController::class, 'show'] );
    Route::post( '/holiday-plans', [HolidayPlanController::class, 'store']);
    Route::put( '/holiday-plans/{id}', [HolidayPlanController::class, 'update']);
    Route::delete( '/holiday-plans/{id}', [HolidayPlanController::class, 'destroy']);
});

