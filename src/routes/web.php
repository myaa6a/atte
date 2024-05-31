<?php

use App\Http\Controllers\StampController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

Route::middleware('auth', 'verified', 'web')->group(function () {
    Route::get('/', [StampController::class, 'index']);
    Route::post('/timein', [StampController::class, 'timein']);
    Route::post('/restin', [StampController::class, 'restin']);
    Route::post('/restout', [StampController::class, 'restout']);
    Route::post('/timeout', [StampController::class, 'timeout']);
    Route::post('/switch', [StampController::class, 'switch']);
    Route::get('/attendance', [StampController::class, 'attendance']);
    Route::get('/day_search', [StampController::class, 'daysearch']);
    Route::get('/user', [StampController::class, 'user']);
    Route::get('/user_search', [StampController::class, 'usersearch']);
});