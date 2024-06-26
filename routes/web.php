<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SneakerController;

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

Route::post('/api/users/resettoken', [AuthController::class, 'send_reset_password_code']);

Route::post('/api/users/resetpassword', [AuthController::class, 'reset_forgotten_password']);

Route::get('/api/users/resettokens', [AuthController::class, 'fetch_reset_tokens']);

Route::post('/api/users/login', [AuthController::class, 'handle_login']);


// SNEAKER ROUTES

Route::post('/api/sneakers/add', [SneakerController::class, 'store']);

Route::get('/api/sneakers', [SneakerController::class, 'index']);

Route::get('/api/sneakers/{id}', [SneakerController::class, 'show']);

Route::delete('/api/sneakers/{id}/delete', [SneakerController::class, 'destroy']);

Route::put('/api/sneakers/{id}/update', [SneakerController::class, 'update']);

// ACCOUNT ROUTES
Route::get('/api/accounts', [AccountController::class, 'index']);

Route::post('/api/accounts/deposit', [AccountController::class, 'deposit']);

// ORDER ROUTES
Route::post('/api/make/order', [OrderController::class, 'createOrder']);

Route::get('/api/orders', [OrderController::class, 'fetchAllOrders']);

Route::get('/api/{id}/orders', [OrderController::class, 'fetchSingleUserOrders']);

Route::get('/api/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);

Route::get('/api/orders/{id}/complete', [OrderController::class, 'completeOrder']);

// REVIEW ROUTES
Route::post('/api/add/sneaker/review', [ReviewController::class, 'createReview']);

Route::get('/api/reviews', [ReviewController::class, 'fetchReviews']);

Route::get('/api/reviews/user/{id}', [ReviewController::class, 'fetchUserReviews']);

Route::get('/api/reviews/sneakers/{id}', [ReviewController::class, 'fetchSneakerReviews']);

Route::delete('/api/reviews/{id}/delete', [ReviewController::class, 'deleteReview']);