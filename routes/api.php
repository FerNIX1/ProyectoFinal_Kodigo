<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\UserController;
use App\Http\Middleware\JsonUnauthenticated;
use App\Http\Controllers\PedidoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api'); 
});

Route::group(['middleware' => AuthMiddleware::class, 'prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'getAllProducts']);
    Route::post('new', [ProductController::class, 'createProduct']);
    Route::get('{id}', [ProductController::class, 'getProductById']);
    Route::patch('{id}', [ProductController::class, 'updateProduct']);
});

Route::group([
    'middleware' => ['auth:api'],
    'prefix' => 'profile'
], function () {
    Route::get('/', [UserController::class, 'show']);
    Route::get('{id}', [UserController::class, 'searchUser']);
    Route::patch('{id}', [UserController::class, 'updateUser']);
});

Route::group([
    'middleware' => AuthMiddleware::class, 
    'prefix' => 'orders'
], function () {
    Route::get('/', [PedidoController::class, 'getAllPedidos']);
    Route::post('new', [PedidoController::class, 'createPedido']);
    Route::get('{id}', [PedidoController::class, 'getPedidoById']);
    Route::patch('{id}', [PedidoController::class, 'updatePedido']);
});
