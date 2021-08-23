<?php

use App\Http\Controllers\API;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth;

Route::post('login', [Auth\LoginController::class, 'login']);
Route::post('register', Auth\RegisterController::class);
Route::post('verification', [Auth\VerificationController::class, 'verify']);
Route::post('again/verification', [Auth\VerificationController::class, 'sendVerificationEmail'])->middleware(['throttle:1,60']);

Route::group(['middleware' => ['auth:api', 'verified']], function (){
    Route::get('/me', [API\UserController::class, 'me']);
    Route::get('/logout', [Auth\LoginController::class, 'logout']);
    Route::apiResource('todo', API\TodoController::class);
});
