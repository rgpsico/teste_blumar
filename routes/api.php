<?php

/**
 * @OA\Info(
 * title="Real Estate Management API",
 * version="1.0.0",
 * description="API para gerenciamento de imóveis, proprietários e inquilinos."
 * )
 */

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\ImageUploadController;
use App\Http\Controllers\Api\OwnerController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\PropertyMessageController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\BeachHouseController;
use Illuminate\Support\Facades\Route;

// Rotas públicas de FAQs
Route::get('/faqs', [ChatController::class, 'index']);
Route::get('/properties/{propertyId}/faqs', [ChatController::class, 'index']);

// Rotas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);
Route::get('/communities', [CommunityController::class, 'index']);

// Mensagens públicas (envio de pergunta sobre imóvel)
Route::post('/properties/{propertyId}/messages', [PropertyMessageController::class, 'store']);


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

    // Chat/FAQs
    Route::post('/properties/{propertyId}/faqs', [ChatController::class, 'store']);
    Route::delete('/faqs/{id}', [ChatController::class, 'destroy']);
    Route::post('/properties/{propertyId}/ask-ai', [ChatController::class, 'askAi']);

    // Tenants
    Route::apiResource('tenants', TenantController::class);

    // Property Messages (Owner)
    Route::get('/owner/messages', [PropertyMessageController::class, 'getOwnerMessages']);
    Route::put('/messages/{messageId}/read', [PropertyMessageController::class, 'markAsRead']);
    Route::put('/messages/read-all', [PropertyMessageController::class, 'markAllAsRead']);
    Route::delete('/messages/{messageId}', [PropertyMessageController::class, 'deleteMessage']);

    // Analytics (Admin only)
    Route::get('/admin/analytics', [AnalyticsController::class, 'getAdminAnalytics']);
    Route::get('/admin/visitor-logs', [AnalyticsController::class, 'getVisitorLogs']);
    Route::get('/admin/registration-logs', [AnalyticsController::class, 'getRegistrationLogs']);
    Route::get('/admin/logs-dashboard', [AnalyticsController::class, 'getLogsDashboard']);

    // Owners (Admin only)
    Route::apiResource('owners', OwnerController::class);

    // Communities (Admin CRUD)
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::put('/communities/{id}', [CommunityController::class, 'update']);
    Route::delete('/communities/{id}', [CommunityController::class, 'destroy']);

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
