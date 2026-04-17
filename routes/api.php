<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PulsaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/pulsa', [PulsaController::class, 'index']);
Route::post('/payment', [PaymentController::class, 'store']);
Route::post('/payment/{id}/success', [PaymentController::class, 'success']);
// Route::post('/payment/{id}/success', [PaymentController::class, 'updateStatus']);
// Route::post('/payment/{id}/confirm', [PaymentController::class, 'success']); // endpoint baru untuk konfirmasi pembayaran
Route::get('/user/{phone}', [UserController::class, 'getUser']);
Route::get('/payments/{phone}', [PaymentController::class, 'history']);