<?php

use App\Http\Controllers\CRUDServidorEfetivo;
use Illuminate\Support\Facades\Route;


Route::get('/test-db', [CRUDServidorEfetivo::class, 'index']);

Route::post('/upload-image', [ImageController::class, 'upload']);
