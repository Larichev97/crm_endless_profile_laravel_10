<?php

use App\Http\Controllers\Admin\ContactForm\AdminContactFormController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('contact-forms', AdminContactFormController::class);
*/

Route::prefix('contact-forms')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [AdminContactFormController::class, 'index'])->name('admin.contact-forms.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [AdminContactFormController::class, 'create'])->name('admin.contact-forms.create');

    // Store: сохранение нового ресурса
    Route::post('/', [AdminContactFormController::class, 'store'])->name('admin.contact-forms.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [AdminContactFormController::class, 'show'])->name('admin.contact-forms.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [AdminContactFormController::class, 'edit'])->name('admin.contact-forms.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [AdminContactFormController::class, 'update'])->name('admin.contact-forms.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [AdminContactFormController::class, 'destroy'])->name('admin.contact-forms.destroy');
});
