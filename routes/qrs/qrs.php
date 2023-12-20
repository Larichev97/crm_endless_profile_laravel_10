<?php

use App\Http\Controllers\QrProfile\QrProfileController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('qrs', QrProfileController::class);
*/

Route::prefix('qrs')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [QrProfileController::class, 'index'])->name('qrs.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [QrProfileController::class, 'create'])->name('qrs.create');

    // Store: сохранение нового ресурса
    Route::post('/', [QrProfileController::class, 'store'])->name('qrs.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [QrProfileController::class, 'show'])->name('qrs.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [QrProfileController::class, 'edit'])->name('qrs.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [QrProfileController::class, 'update'])->name('qrs.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [QrProfileController::class, 'destroy'])->name('qrs.destroy');

    // Generate Qr Code
    Route::get('/generate-qr-code/{id}', [QrProfileController::class, 'generateQrCodeImage'])->name('qrs.generate-qr-code-image');
});
