<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return redirect('/api/documentation');
    // return view('welcome');
});

Route::get('/exported/file/{filename}', function ($filename) {
    $filePath = "public/exported/$filename";

    if (!Storage::disk('local')->exists($filePath)) {
        abort(404);
    }

    return response()->file(storage_path("app/$filePath"));
})->name('exported.file')->middleware('signed');