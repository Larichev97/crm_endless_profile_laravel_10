<?php

use App\Http\Controllers\Country\CountryController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('countries', CountryController::class);
*/

Route::prefix('countries')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [CountryController::class, 'index'])->name('countries.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [CountryController::class, 'create'])->name('countries.create');

    // Store: сохранение нового ресурса
    Route::post('/', [CountryController::class, 'store'])->name('countries.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [CountryController::class, 'show'])->name('countries.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [CountryController::class, 'edit'])->name('countries.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [CountryController::class, 'update'])->name('countries.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [CountryController::class, 'destroy'])->name('countries.destroy');
});
