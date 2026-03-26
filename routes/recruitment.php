<?php

use App\Http\Controllers\Recruitment\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::get('department/filter', [DepartmentController::class, 'filter'])->name('department.filter');
Route::resource('department', DepartmentController::class);
