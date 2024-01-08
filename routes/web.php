<?php

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

Route::get('', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['as' => 'category.', 'prefix' => 'categories'], function(){
    Route::get('{category}', [\App\Http\Controllers\CategoryController::class, 'index'])->name('index');
    Route::get('{category}/filter', [\App\Http\Controllers\CategoryController::class, 'filter'])->name('filter');
});
