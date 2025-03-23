<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PessoaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Login (gera token)
Route::post('/login', [AuthController::class, 'login']);

// Logout (revoga token)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Renovação do Token
Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

// Exemplo de rota protegida
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/pessoa', [PessoaController::class, 'index']);

Route::post('/store-pessoa', [PessoaController::class, 'store'])->middleware('auth:sanctum');
Route::put('/update-pessoa/{pes_id}', [PessoaController::class, 'update'])->middleware('auth:sanctum');
