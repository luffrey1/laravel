<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComicApiController;

Route::get('/comics', [ComicApiController::class, 'index']);  
Route::get('/comics/{id}', [ComicApiController::class, 'show']);  
Route::post('/comics', [ComicApiController::class, 'store']);
Route::put('/comics/{id}', [ComicApiController::class, 'update']);  
Route::delete('/comics/{id}', [ComicApiController::class, 'destroy']);