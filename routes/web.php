<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ComicController;

//Para que se muestre la pagina de index nada mas entrar a la ip.
Route::get('/', [ComicController::class, 'index']);




// para registrarte
Route::get('user/register', [UserController::class, 'create'])->name('register');
Route::post('user/register', [UserController::class, 'store'])->name('register.store');
//para logearte
Route::get('user/login', [UserController::class, 'login'])->name('login');
Route::post('user/login', [UserController::class, 'authenticate'])->name('login.authenticate');
// cerrar sesion
Route::post('user/logout', [UserController::class, 'logout'])->name('logout');
// crear comics(solo admins)
Route::get('/comics/create', [ComicController::class, 'create'])->name('comics.create');  
//para guardar los libros correctamente
Route::post('/comics', [ComicController::class, 'store'])->name('comics.store');
// se muestran los comics
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
//eliminar libros(solo admins)
Route::delete('/comics/{comic}', [ComicController::class, 'destroy'])->name('comics.delete');


// Esto solo para admins
Route::get('/users', [UserController::class, 'index'])->name('user.index'); 
Route::post('/users/{id}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('user.toggleAdmin');

// carrito
// el index de carrito donde se muestra todo
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// para añadir comics al carrito
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
// para remover eliminar comics del carrito
Route::post('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
// mensaje de success al añadir
Route::get('/cart/success', [CartController::class, 'success'])->name('cart.success');
