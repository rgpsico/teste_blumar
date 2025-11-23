<?php

use App\Http\Controllers\BeachHouseController;
use Illuminate\Support\Facades\Route;

// Vue SPA - todas as rotas vão para a aplicação Vue
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
