<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () use($router) {
    $router->post('/posts', [PostController::class, 'store']);
    $router->post('/posts/{id}', [PostController::class, 'update'])->where('id', '[0-9]+');
    $router->delete('/posts/{id}', [PostController::class, 'destroy'])->where('id', '[0-9]+');
    $router->delete('/posts/{id}/photo/{pid}', [PostController::class, 'destroyPhoto'])->where('id', '[0-9]+');
    
    $router->get('/users/{id}', [UserController::class, 'show'])->where('id', '[0-9]+');
    $router->get('/users', [UserController::class, 'index']);
    $router->post('/users', [UserController::class, 'store']);
    $router->post('/users/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
    $router->delete('/users/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+');
});

Route::prefix('v1')->group(function () use($router) {
    $router->get('/posts', [PostController::class, 'index']);
    $router->get('/posts/{id}', [PostController::class, 'show'])->where('id', '[0-9]+');
    
    $router->post('/register', [UserController::class, 'register']);
    $router->post('/activate', [UserController::class, 'activate']);
    $router->post('/login', [UserController::class, 'login']);
    
    $router->post('/forgot-password', [UserController::class, 'forgotPassword']);
    $router->post('/reset-password', [UserController::class, 'resetPassword']);
});
