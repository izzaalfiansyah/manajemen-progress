<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Controller;
use App\Http\Middleware\Control;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [Controller\UserController::class, 'login']);

Route::middleware(Control::class)->group(function () {
    Route::resource('/user/tugas/pengumpulan', Controller\UserTugasPengumpulanController::class);
    Route::resource('/user/tugas', Controller\UserTugasController::class);
    Route::resource('/user', Controller\UserController::class);
});
