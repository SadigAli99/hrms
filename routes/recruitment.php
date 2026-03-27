<?php

use App\Http\Controllers\Recruitment\DepartmentController;
use App\Http\Controllers\Recruitment\VacancyController;
use Illuminate\Support\Facades\Route;

Route::get('department/filter', [DepartmentController::class, 'filter'])->name('department.filter');
Route::resource('department', DepartmentController::class);

Route::get('vacancy/filter',[VacancyController::class,'filter'])->name('vacancy.filter');
Route::resource('vacancy', VacancyController::class);
