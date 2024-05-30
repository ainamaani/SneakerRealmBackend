<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomUserController;

Route::post('/signup', [CustomUserController::class, 'store']);

Route::get('/users', [CustomUserController::class, 'index']);

Route::get('/users/{id}', [CustomUserController::class, 'show']);

Route::delete('/users/{id}/delete', [CustomUserController::class, 'destroy']);

Route::put('/users/{id}/delete', [CustomUserController::class, 'update']);
