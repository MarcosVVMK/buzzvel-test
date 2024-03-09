<?php

use App\Http\Controllers\Api\V1\HolidayPlanController;
use App\Http\Controllers\AuthController;
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
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');

    Route::middleware('auth:sanctum')->group(function (){

        Route::get('/holiday-plans', [HolidayPlanController::class, 'index'] )->name('holiday.index');

        Route::get('/holiday-plans/{id}', [HolidayPlanController::class, 'show'] )->name('holiday.show');

        Route::post( '/holiday-plans', [HolidayPlanController::class, 'store'])->name('holiday.store');

        Route::put( '/holiday-plans/{id}', [HolidayPlanController::class, 'update'])->name('holiday.update');

        Route::delete( '/holiday-plans/{id}', [HolidayPlanController::class, 'destroy'])->name('holiday.destroy');

        Route::get( '/download-pdf/{id}', [HolidayPlanController::class, 'download'])->name('holiday.download');

        Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

    });

});

