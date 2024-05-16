<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/token', function (){
    return csrf_token();
});

// USER RELATED ROUTES

Route::post('/api/auth/signup', [CustomUserController::class, 'store']);

Route::get('/api/auth/users', [CustomUserController::class, 'index']);

Route::get('/api/auth/users/{id}', [CustomUserController::class, 'show']);

Route::delete('/api/auth/users/{id}/delete', [CustomUserController::class, 'destroy']);

Route::put('/api/auth/users/{id}/update', [CustomUserController::class, 'update']);

// AUTHENTICATION RELATED ROUTES

Route::post('/api/users/{id}/changepassword', [AuthController::class, 'change_password']);