<?php

use App\Http\Controllers\CalcController;
use App\Http\Controllers\HelloController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', [HelloController::class, 'hello']);
Route::get('/hello/{count}', [HelloController::class, 'helloMany']);



Route::get('/calc', [CalcController::class, 'calc']);
