<?php

use App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth;

Route::post('login', [Auth\LoginController::class, 'login']);
Route::post('register', Auth\RegisterController::class);

Route::group(['middleware' => ['auth:api', 'verified']], function (){
    Route::get('/me', [API\UserController::class, 'me']);
    Route::get('/logout', [Auth\LoginController::class, 'logout']);

    Route::apiResource('todo', API\TodoController::class);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
