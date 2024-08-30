<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\StateController;
use App\Http\Controllers\api\TagController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(AuthController::class)->group(function (){
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/send', 'send');
    Route::post('/verify', 'verify');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function (){
    Route::resource('/post',PostController::class);
    Route::resource('/tag',TagController::class);
    Route::resource('/state',StateController::class);
});
