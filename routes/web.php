<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComicController;
Route::get('/', function () {
    return view('welcome');
});





Route::get('user/register', [UserController::class, 'create'])->name('register');
Route::post('user/register', [UserController::class, 'store'])->name('register.store');

Route::get('user/login', [UserController::class, 'login'])->name('login');
Route::post('user/login', [UserController::class, 'authenticate'])->name('login.authenticate');

Route::post('user/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/comics', [ComicController::class, 'create'])->name('comics.create');  

Route::post('/comics', [ComicController::class, 'store'])->name('comics.store');

Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');



Route::delete('/comics/{comic}', [ComicController::class, 'destroy'])->name('comics.delete');


// Esto solo para admins
Route::get('/users', [UserController::class, 'index'])->name('user.index'); 
Route::post('/users/{id}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('user.toggleAdmin');
