<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductAttributeApiController;
use App\Http\Controllers\Api\ProductValueApiController;
use App\Http\Controllers\Api\UserApiController;


Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{id}', [ProductApiController::class, 'show']);


Route::get('/categories', [CategoryApiController::class, 'index']);
Route::post('/categories', [CategoryApiController::class, 'store']);
Route::get('/categories/{id}', [CategoryApiController::class, 'show']);
Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);


Route::get('/attributes', [ProductAttributeApiController::class, 'index']);
Route::post('/attributes', [ProductAttributeApiController::class, 'store']);
Route::get('/attributes/{id}', [ProductAttributeApiController::class, 'show']);
Route::put('/attributes/{id}', [ProductAttributeApiController::class, 'update']);
Route::delete('/attributes/{id}', [ProductAttributeApiController::class, 'destroy']);

Route::get('/values', [ProductValueApiController::class, 'index']);
Route::get('/values/{id}', [ProductValueApiController::class, 'show']);
Route::post('/values', [ProductValueApiController::class, 'store']);
Route::put('/values/{id}', [ProductValueApiController::class, 'update']);
Route::delete('/values/{id}', [ProductValueApiController::class, 'destroy']);

Route::get('/users', [UserApiController::class, 'index']);
Route::get('/users/{id}', [UserApiController::class, 'show']);
Route::post('/users', [UserApiController::class, 'store']);
Route::put('/users/{id}', [UserApiController::class, 'update']);
Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
Route::put('users/{user}', [UserApiController::class, 'update']);
Route::patch('users/{user}', [UserApiController::class, 'update']);


use App\Http\Controllers\Api\VoucherApiController;
Route::Resource('vouchers', VoucherApiController::class);

use App\Http\Controllers\Api\PostCategoryApiController;
Route::Resource('post_categories', PostCategoryApiController::class);

use App\Http\Controllers\Api\PostApiController;
Route::Resource('posts', PostApiController::class);

// use App\Http\Controllers\Api\CommentApiController;
// Route::Resource('comments', CommentApiController::class);

use App\Http\Controllers\Api\ProductReviewApiController;
Route::Resource('product_reviews', ProductReviewApiController::class);

use App\Http\Controllers\Api\PostCommentApi;
Route::Resource('comments', PostCommentApi::class);

use App\Http\Controllers\Api\CourtApi;

Route::Resource('courts', CourtApi::class);

use App\Http\Controllers\Api\CourtBookingApi;

Route::Resource('court_bookings', CourtBookingApi::class);

use App\Http\Controllers\Api\CartApi;

Route::Resource('carts', CartApi::class);

use App\Http\Controllers\Api\OrderDetailApi;
Route::Resource('order_details', OrderDetailApi::class);

use App\Http\Controllers\Api\OrderApi;

Route::apiResource('orders', OrderApi::class);

use App\Http\Controllers\Api\RolesApiController;

Route::apiResource('roles', RolesApiController::class);
