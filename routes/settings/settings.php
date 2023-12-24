<?php

use App\Http\Controllers\Setting\SettingController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('settings', SettingController::class);
*/

Route::prefix('settings')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [SettingController::class, 'index'])->name('settings.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [SettingController::class, 'create'])->name('settings.create');

    // Store: сохранение нового ресурса
    Route::post('/', [SettingController::class, 'store'])->name('settings.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [SettingController::class, 'show'])->name('settings.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [SettingController::class, 'edit'])->name('settings.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [SettingController::class, 'update'])->name('settings.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [SettingController::class, 'destroy'])->name('settings.destroy');
});
