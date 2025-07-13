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
