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
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/send', 'send')->name('send');
    Route::post('/verify', 'verify')->name('verify');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function (){
    Route::controller(PostController::class)->group(function (){
        Route::patch('posts/pin/{post}', 'pin');
        Route::get('posts/trash', 'trash');
        Route::post('posts/restore/{id}', 'restore');
    });
    Route::apiResources([
        'posts' => PostController::class,
    ]);
});
