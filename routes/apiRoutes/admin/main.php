<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;

Route::post('register', [RegisterController::class, 'registerAdmin']);
Route::post('login', [RegisterController::class, 'loginAdmin']);
