<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/profile', fn() => view('auth.profile'))->name('profile');
Route::post('/update-profile', [LoginController::class, 'update_profile'])->name('update.profile');
Route::delete('/delete-image', [LoginController::class, 'delete_image'])->name('delete.image');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
