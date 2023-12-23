<?php


use App\Http\Controllers\City\CityController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('cities', CityController::class);
*/

Route::prefix('cities')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [CityController::class, 'index'])->name('cities.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [CityController::class, 'create'])->name('cities.create');

    // Store: сохранение нового ресурса
    Route::post('/', [CityController::class, 'store'])->name('cities.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [CityController::class, 'show'])->name('cities.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [CityController::class, 'edit'])->name('cities.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [CityController::class, 'update'])->name('cities.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [CityController::class, 'destroy'])->name('cities.destroy');
});
