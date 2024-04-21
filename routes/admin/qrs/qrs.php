<?php

use App\Http\Controllers\Admin\QrProfile\AdminQrProfileController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('qrs', AdminQrProfileController::class);
*/

Route::prefix('qrs')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [AdminQrProfileController::class, 'index'])->name('admin.qrs.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [AdminQrProfileController::class, 'create'])->name('admin.qrs.create');

    // Store: сохранение нового ресурса
    Route::post('/', [AdminQrProfileController::class, 'store'])->name('admin.qrs.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [AdminQrProfileController::class, 'show'])->name('admin.qrs.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [AdminQrProfileController::class, 'edit'])->name('admin.qrs.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [AdminQrProfileController::class, 'update'])->name('admin.qrs.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [AdminQrProfileController::class, 'destroy'])->name('admin.qrs.destroy');

    // Generate Qr Code
    Route::get('/generate-qr-code/{id}', [AdminQrProfileController::class, 'generateQrCodeImage'])->name('admin.qrs.generate-qr-code-image');

    // Gallery ##############################################################################################################################

    // Create photos for Gallery:
    Route::get('/gallery/{id}/create', [AdminQrProfileController::class, 'createGalleryImages'])->name('admin.qrs.create-gallery-images');

    // Store photos for Gallery:
    Route::post('/gallery', [AdminQrProfileController::class, 'storeGalleryImages'])->name('admin.qrs.store-gallery-images');

    // Destroy photo in gallery:
    Route::delete('/gallery/{id}', [AdminQrProfileController::class, 'destroyGalleryImage'])->name('admin.qrs.destroy-gallery-image');

    // ######################################################################################################################################
});
