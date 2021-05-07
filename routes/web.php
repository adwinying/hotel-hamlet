<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoomTypeController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/', [HomeController::class, 'show']);
        Route::get('/profile', [ProfileController::class, 'edit']);
        Route::post('/profile', [ProfileController::class, 'update']);

        Route::resource('/hotels', HotelController::class);
        Route::resource('/room_types', RoomTypeController::class);
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'show'])->name('login');
        Route::post('/login', [AuthController::class, 'store']);
    });
});

Route::get('/', function () {
    return view('welcome');
});
