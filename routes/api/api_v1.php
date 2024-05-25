<?php

use App\Http\Controllers\Api\V1\QrProfile\ShowController;
use App\Http\Controllers\Api\V1\QrProfile\UpdateController;
use App\Http\Controllers\Api\V1\QrProfile\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('qrs')->group(function () {
    // Получение данных конкретного QR-профиля [https://crm-qr-laravel.loc/api/v1/qrs/:id]:
    Route::get('/{id}', [ShowController::class, 'show'])->name('api.qrs.show');

    // Обновление данных у конкретного QR-профиля [https://crm-qr-laravel.loc/api/v1/qrs/:id]:
    Route::put('/{id}', [UpdateController::class, 'update'])->name('api.qrs.update')->middleware('auth.api.token');

    // Создание нового QR-профиля [https://crm-qr-laravel.loc/api/v1/qrs]:
    Route::post('/', [StoreController::class, 'store'])->name('api.qrs.store')->middleware('auth.api.token');
});
