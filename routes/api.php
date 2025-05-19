<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/teste', [UserController::class, 'teste']);

Route::post('/envio', [UserController::class, 'envio']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
