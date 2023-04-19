<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
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

Route::prefix('admin')->group(function () {
    Route::middleware('auth', 'nocache')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/', [HomeController::class, 'show']);
        Route::get('/profile', [ProfileController::class, 'edit']);
        Route::post('/profile', [ProfileController::class, 'update']);

        Route::resource('/hotels', HotelController::class);
        Route::resource('/room_types', RoomTypeController::class);
        Route::resource('/rooms', RoomController::class);
        Route::resource('/reservations', ReservationController::class);
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login/redirect', [AuthController::class, 'redirectToLogin'])->name('login.redirect');
        Route::get('/login', [AuthController::class, 'show'])->name('login');
        Route::post('/login', [AuthController::class, 'store']);
    });
});

Route::get('/', function () {
    return view('welcome');
});
