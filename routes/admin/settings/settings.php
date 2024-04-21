<?php

use App\Http\Controllers\Admin\Setting\AdminSettingController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('settings', AdminSettingController::class);
*/

Route::prefix('settings')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [AdminSettingController::class, 'index'])->name('admin.settings.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [AdminSettingController::class, 'create'])->name('admin.settings.create');

    // Store: сохранение нового ресурса
    Route::post('/', [AdminSettingController::class, 'store'])->name('admin.settings.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [AdminSettingController::class, 'show'])->name('admin.settings.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [AdminSettingController::class, 'edit'])->name('admin.settings.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [AdminSettingController::class, 'update'])->name('admin.settings.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [AdminSettingController::class, 'destroy'])->name('admin.settings.destroy');
});
