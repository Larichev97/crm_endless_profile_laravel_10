<?php

use App\Http\Controllers\Admin\Auth\AdminChangePassword;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminRegisterController;
use App\Http\Controllers\Admin\Auth\AdminResetPassword;
use Illuminate\Support\Facades\Route;


Route::post('logout', [AdminLoginController::class, 'logout'])->middleware('auth')->name('admin.logout');
Route::get('register', [AdminRegisterController::class, 'create'])->middleware('guest')->name('admin.register');
Route::post('register', [AdminRegisterController::class, 'store'])->middleware('guest')->name('admin.register.perform');
Route::get('login', [AdminLoginController::class, 'show'])->middleware('guest')->name('admin.login');
Route::post('login', [AdminLoginController::class, 'login'])->middleware('guest')->name('admin.login.perform');
Route::get('reset-password', [AdminResetPassword::class, 'show'])->middleware('guest')->name('admin.reset-password');
Route::post('reset-password', [AdminResetPassword::class, 'send'])->middleware('guest')->name('admin.reset.perform');
Route::get('change-password', [AdminChangePassword::class, 'show'])->middleware('guest')->name('admin.change-password');
Route::post('change-password', [AdminChangePassword::class, 'update'])->middleware('guest')->name('admin.change.perform');
