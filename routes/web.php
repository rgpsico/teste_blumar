<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImovelController;
use App\Http\Controllers\CepController;
use Illuminate\Support\Facades\Route;

// Rota principal
Route::get('/', [ImovelController::class, 'home'])->name('home');

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/meus-imoveis', [ImovelController::class, 'myProperties'])->name('imoveis.my');
    Route::resource('imoveis', ImovelController::class)->except(['index']);
});

// Rota pública para busca de CEP
Route::get('/cep/{cep}', [CepController::class, 'show']);

// Rotas públicas de imóveis
Route::get('/imoveis/search', [ImovelController::class, 'search'])->name('imoveis.search');
