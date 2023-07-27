<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVarificationMiddleware;
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

Route::get('/', function () {
    return view('welcome');
});


// User Section
Route::post('/user-register', [UserController::class, 'userRegistration']);
Route::post('/user-login', [UserController::class, 'userLogin']);
Route::post('/send-otp', [UserController::class, 'sendOTPToEmail']);
Route::post('/otp-varify', [UserController::class, 'OTPVarification']);

Route::post('/reset-password', [UserController::class, 'resetPassword'])
->middleware([TokenVarificationMiddleware::class]);
Route::post('/profile-update', [UserController::class, 'profileUpdate']);

