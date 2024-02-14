<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Institution\InstitutionController;
use App\Http\Middleware\VerifyAdminToken;


// Route::post('register', [InstitutionController::class, 'register'])->middleware(VerifyAdminToken::class);
Route::post('register', [InstitutionController::class, 'register']);
Route::post('login', [InstitutionController::class, 'login']);
Route::get('/search', [InstitutionController::class, 'search']);

Route::get('/', [InstitutionController::class, 'getAll']);
