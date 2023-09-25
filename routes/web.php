<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Panel\PostController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Middleware\AuthenticateToken;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$router->middleware([AuthenticateToken::class, 'web'])->group(function () use($router) {
    $router->get('/post/create', [PostController::class, 'create'])->name('post.create');
    $router->post('/post/create', [PostController::class, 'store'])->name('post.store');
    $router->get('/post/{id}', [PostController::class, 'edit'])->name('post.edit');
    $router->post('/post/{id}', [PostController::class, 'update'])->name('post.update');
    $router->post('/post/delete/{id}', [PostController::class, 'delete'])->name('post.delete');
    $router->post('/post/{id}/photo/{pid}', [PostController::class, 'deletePhoto'])->name('post.delete.photo');
    
    $router->get('/users', [UserController::class, 'index'])->name('users');
    $router->get('/user/create', [UserController::class, 'create'])->name('user.create');
    $router->post('/user/create', [UserController::class, 'store'])->name('user.store');
    $router->get('/user/{id}', [UserController::class, 'edit'])->name('user.edit');
    $router->post('/user/{id}', [UserController::class, 'update'])->name('user.update');
    $router->post('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    
    $router->get('/logout', [UserController::class, 'logout'])->name('user.logout');
});

$router->middleware(['web'])->group(function () use($router) {
    $router->get('/', [PostController::class, 'index'])->name('index');
    $router->get('/post/show/{id}', [PostController::class, 'show'])->name('post.show');
    
    $router->get('/login', [UserController::class, 'login'])->name('user.login');
    $router->post('/login', [UserController::class, 'loginPost'])->name('user.login.post');
    $router->get('/register', [UserController::class, 'register'])->name('user.register');
    $router->post('/register', [UserController::class, 'registerPost'])->name('user.register.post');
    $router->get('/register/ok', [UserController::class, 'registerOk'])->name('user.register.ok');
    
    $router->get('/activate', [UserController::class, 'activate'])->name('user.activate');
    $router->get('/forgot-password', [UserController::class, 'forgotPassword'])->name('user.forgot_password');
    $router->post('/forgot-password', [UserController::class, 'forgotPasswordPost'])->name('user.forgot_password.post');
    $router->get('/forgot-password/ok', [UserController::class, 'forgotPasswordOk'])->name('user.forgot_password.ok');
    $router->get('/reset-password', [UserController::class, 'resetPassword'])->name('user.reset_password');
    $router->post('/reset-password', [UserController::class, 'resetPasswordPost'])->name('user.reset_password.post');
    $router->get('/reset-password/ok', [UserController::class, 'resetPasswordOk'])->name('user.reset_password.ok');
});

