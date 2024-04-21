<?php

use App\Http\Controllers\Admin\Country\AdminCountryController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('countries', AdminCountryController::class);
*/

Route::prefix('countries')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [AdminCountryController::class, 'index'])->name('admin.countries.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [AdminCountryController::class, 'create'])->name('admin.countries.create');

    // Store: сохранение нового ресурса
    Route::post('/', [AdminCountryController::class, 'store'])->name('admin.countries.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [AdminCountryController::class, 'show'])->name('admin.countries.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [AdminCountryController::class, 'edit'])->name('admin.countries.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [AdminCountryController::class, 'update'])->name('admin.countries.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [AdminCountryController::class, 'destroy'])->name('admin.countries.destroy');
});
