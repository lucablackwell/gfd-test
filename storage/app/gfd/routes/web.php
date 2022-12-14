<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [EmployeeController::class, 'index'])->name('employee.index');
Route::get('/edit/{employee}', [EmployeeController::class, 'edit'])->name('employee.edit');
Route::post('/update/{employee}', [EmployeeController::class, 'update'])->name('employee.update');