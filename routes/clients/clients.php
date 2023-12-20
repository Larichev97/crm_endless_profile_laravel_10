<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('clients', ClientController::class);
*/

Route::prefix('clients')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [ClientController::class, 'create'])->name('clients.create');

    // Store: сохранение нового ресурса
    Route::post('/', [ClientController::class, 'store'])->name('clients.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [ClientController::class, 'show'])->name('clients.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [ClientController::class, 'update'])->name('clients.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
});
