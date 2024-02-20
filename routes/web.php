<?php

use App\Http\Controllers\AppointmentDestroyController;
use App\Http\Controllers\AppointmentShowController;
use App\Http\Controllers\AppointmentStoreController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EmployeeServiceIndexController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/employees/{employee:slug}', EmployeeServiceIndexController::class)->name('employee');
Route::get('/checkout/{service:slug}/{employee:slug?}', CheckoutController::class)->name('checkout')->scopeBindings();
Route::post('/appointments', AppointmentStoreController::class)->name('appointments.store');
Route::get('/appointments/{appointment:uuid}', AppointmentShowController::class)->name('appointments.show');
Route::delete('/appointments/{appointment:uuid}', AppointmentDestroyController::class)->name('appointments.destroy');
