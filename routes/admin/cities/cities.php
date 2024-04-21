<?php

use App\Http\Controllers\Admin\City\AdminCityController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('cities', AdminCityController::class);
*/

Route::prefix('cities')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [AdminCityController::class, 'index'])->name('admin.cities.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [AdminCityController::class, 'create'])->name('admin.cities.create');

    // Store: сохранение нового ресурса
    Route::post('/', [AdminCityController::class, 'store'])->name('admin.cities.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [AdminCityController::class, 'show'])->name('admin.cities.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [AdminCityController::class, 'edit'])->name('admin.cities.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [AdminCityController::class, 'update'])->name('admin.cities.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [AdminCityController::class, 'destroy'])->name('admin.cities.destroy');
});
