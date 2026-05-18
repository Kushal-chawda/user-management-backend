<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/register', [
    AuthController::class,
    'register'
]);

Route::post('/login', [
    AuthController::class,
    'login'
]);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

    Route::get('/profile', [
        AuthController::class,
        'profile'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource(
        'users',
        UserController::class
    );
});
Route::middleware('auth:sanctum')
   ->post('/logout', [

      AuthController::class,
      'logout'
   ]);
