<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductAttributeApiController;
use App\Http\Controllers\Api\ProductValueApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\VoucherApiController;
use App\Http\Controllers\Api\PostCategoryApiController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\ProductReviewApiController;
use App\Http\Controllers\Api\PostCommentApi;
use App\Http\Controllers\Api\CourtApi;
use App\Http\Controllers\Api\CourtBookingApi;
use App\Http\Controllers\Api\CartApi;
use App\Http\Controllers\Api\OrderDetailApi;
use App\Http\Controllers\Api\OrderApi;
use App\Http\Controllers\Api\RolesApiController;

// Products
Route::get('/products', [ProductApiController::class, 'index']);

Route::get('/products/{id}', [ProductApiController::class, 'show']);

// Categories
Route::get('/categories', [CategoryApiController::class, 'index']);

Route::post('/categories', [CategoryApiController::class, 'store']);

Route::get('/categories/{id}', [CategoryApiController::class, 'show']);

Route::put('/categories/{id}', [CategoryApiController::class, 'update']);

Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);

// Attributes
Route::get('/attributes', [ProductAttributeApiController::class, 'index']);

Route::post('/attributes', [ProductAttributeApiController::class, 'store']);

Route::get('/attributes/{id}', [ProductAttributeApiController::class, 'show']);

Route::put('/attributes/{id}', [ProductAttributeApiController::class, 'update']);

Route::delete('/attributes/{id}', [ProductAttributeApiController::class, 'destroy']);

// Values
Route::get('/values', [ProductValueApiController::class, 'index']);

Route::get('/values/{id}', [ProductValueApiController::class, 'show']);

Route::post('/values', [ProductValueApiController::class, 'store']);

Route::put('/values/{id}', [ProductValueApiController::class, 'update']);

Route::delete('/values/{id}', [ProductValueApiController::class, 'destroy']);

// Users
Route::get('/users', [UserApiController::class, 'index']);

Route::get('/users/{id}', [UserApiController::class, 'show']);

Route::post('/users', [UserApiController::class, 'store']);

Route::put('/users/{id}', [UserApiController::class, 'update']);

Route::delete('/users/{id}', [UserApiController::class, 'destroy']);

Route::put('users/{user}', [UserApiController::class, 'update']);

Route::patch('users/{user}', [UserApiController::class, 'update']);

// ✅ Login route thêm tại đây
Route::post('/login', [UserApiController::class, 'login']);

// Các tài nguyên còn lại
Route::resource('vouchers', VoucherApiController::class);

Route::resource('post_categories', PostCategoryApiController::class);

Route::resource('posts', PostApiController::class);

Route::resource('product_reviews', ProductReviewApiController::class);

Route::resource('comments', PostCommentApi::class);

Route::resource('courts', CourtApi::class);

Route::resource('court_bookings', CourtBookingApi::class);

Route::resource('carts', CartApi::class);

Route::resource('order_details', OrderDetailApi::class);

Route::apiResource('orders', OrderApi::class);

Route::apiResource('roles', RolesApiController::class);
