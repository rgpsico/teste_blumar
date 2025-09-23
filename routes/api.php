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


use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');




use App\Http\Controllers\ImovelController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/imoveis/filter', [ImovelController::class, 'filter']);
    Route::get('/imoveis', [ImovelController::class, 'index']);
    Route::get('/imoveis/{id}', [ImovelController::class, 'show']);
    Route::post('/imoveis', [ImovelController::class, 'store']);
    Route::put('/imoveis/{id}', [ImovelController::class, 'update']);
    Route::delete('/imoveis/{id}', [ImovelController::class, 'destroy']);
});

// Lista pública de imóveis
Route::get('/imoveis', [ImovelController::class, 'publicIndex']);
