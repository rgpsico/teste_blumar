<?php


use App\Http\Controllers\BeachHouseController;
use Illuminate\Support\Facades\Route;

Route::get('/beach-houses', [BeachHouseController::class, 'index']);
Route::get('/beach-houses/{id}', [BeachHouseController::class, 'show']);
Route::post('/beach-houses', [BeachHouseController::class, 'store']);
Route::put('/beach-houses/{id}', [BeachHouseController::class, 'update']);
