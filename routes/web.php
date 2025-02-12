<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComicController;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/comics', [ComicController::class, 'index'])->name('comic.index');



Route::get('user/register', [UserController::class, 'create'])->name('register');
Route::post('user/register', [UserController::class, 'store'])->name('register.store');

Route::get('user/login', [UserController::class, 'login'])->name('login');
Route::post('user/login', [UserController::class, 'authenticate'])->name('login.authenticate');

Route::post('user/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');
