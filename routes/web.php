<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/', '/dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('role/filter', [RoleController::class, 'filter'])->name('role.filter');
Route::resource('role', RoleController::class);
Route::get('role/{role}/get-permission', [RoleController::class, 'get_permission'])->name('role.get-permission');
Route::post('role/{role}/assign-permission', [RoleController::class, 'assign_permission'])->name('role.assign-permission');

Route::get('permission/filter', [PermissionController::class, 'filter'])->name('permission.filter');
Route::resource('permission', PermissionController::class);

Route::get('user/filter', [UserController::class, 'filter'])->name('user.filter');
Route::resource('user', UserController::class);
