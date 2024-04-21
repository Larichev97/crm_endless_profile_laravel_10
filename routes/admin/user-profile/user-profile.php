<?php

use App\Http\Controllers\Admin\UserProfile\AdminUserProfileController;
use Illuminate\Support\Facades\Route;


// Сотрудник:
Route::get('profile', [AdminUserProfileController::class, 'show'])->name('admin.profile');
Route::post('profile', [AdminUserProfileController::class, 'update'])->name('admin.profile.update');
