<?php

/**
 * @OA\Info(
 * title="My Awesome API",
 * version="1.0.0",
 * description="A simple API to demonstrate Swagger-PHP."
 * )
 */

use App\Http\Controllers\BeachHouseController;
use Illuminate\Support\Facades\Route;


Route::put('/beach-house/{id}', [App\Http\Controllers\BeachHouseController::class, 'update']);
Route::get('/beach-houses', [App\Http\Controllers\BeachHouseController::class, 'index']);
Route::get('/beach-houses/{id}', [App\Http\Controllers\BeachHouseController::class, 'show']);
Route::post('/beach-houses', [App\Http\Controllers\BeachHouseController::class, 'store']);
Route::delete('/beach-houses/{id}', [App\Http\Controllers\BeachHouseController::class, 'destroy']);
