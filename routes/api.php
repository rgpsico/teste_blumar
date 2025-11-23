<?php

/**
 * @OA\Info(
 * title="Real Estate Management API",
 * version="1.0.0",
 * description="API para gerenciamento de imóveis, proprietários e inquilinos."
 * )
 */

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BeachHouseController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);
Route::get('/communities', [CommunityController::class, 'index']);

// Rotas protegidas (requerem autenticação)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Image Upload
    Route::post('/upload-image', [ImageUploadController::class, 'upload']);
    Route::delete('/delete-image', [ImageUploadController::class, 'delete']);

    // Properties
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::put('/properties/{id}', [PropertyController::class, 'update']);
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);

    // Tenants
    Route::apiResource('tenants', TenantController::class);

    // Users (Admin only)
    Route::middleware('can:admin')->group(function () {
        Route::apiResource('users', UserController::class);
    });
});

// Legacy beach houses routes
Route::put('/beach-house/{id}', [BeachHouseController::class, 'update']);
Route::get('/beach-houses', [BeachHouseController::class, 'index']);
Route::get('/beach-houses/{id}', [BeachHouseController::class, 'show']);
Route::post('/beach-houses', [BeachHouseController::class, 'store']);
Route::delete('/beach-houses/{id}', [BeachHouseController::class, 'destroy']);
