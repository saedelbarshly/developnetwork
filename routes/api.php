<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TagController;

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(AuthController::class)->group(function (){
    Route::post('/signin', 'signin');
    Route::post('/register', 'register');
    Route::post('/send', 'send');
    Route::post('/verify', 'verify');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function (){
    Route::controller(PostController::class)->group(function (){
        Route::patch('posts/pin/{post}', 'pin');
        Route::get('posts/trash', 'trash');
        Route::post('posts/restore/{id}', 'restore');
    });
    Route::apiResources([
        'posts' => PostController::class,
        'tags' => TagController::class,
    ]);
});
