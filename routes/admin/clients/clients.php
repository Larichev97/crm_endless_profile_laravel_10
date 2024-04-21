<?php

use App\Http\Controllers\Admin\Client\AdminClientController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('clients', AdminClientController::class);
*/

Route::prefix('clients')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [AdminClientController::class, 'index'])->name('admin.clients.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [AdminClientController::class, 'create'])->name('admin.clients.create');

    // Store: сохранение нового ресурса
    Route::post('/', [AdminClientController::class, 'store'])->name('admin.clients.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [AdminClientController::class, 'show'])->name('admin.clients.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [AdminClientController::class, 'edit'])->name('admin.clients.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [AdminClientController::class, 'update'])->name('admin.clients.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [AdminClientController::class, 'destroy'])->name('admin.clients.destroy');
});
