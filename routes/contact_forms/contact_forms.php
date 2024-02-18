<?php

use App\Http\Controllers\ContactForm\ContactFormController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('contact-forms', ContactFormController::class);
*/

Route::prefix('contact-forms')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [ContactFormController::class, 'index'])->name('contact-forms.index');

    // Create: отображение формы создания ресурса
    Route::get('/create', [ContactFormController::class, 'create'])->name('contact-forms.create');

    // Store: сохранение нового ресурса
    Route::post('/', [ContactFormController::class, 'store'])->name('contact-forms.store');

    // Show: отображение конкретного ресурса
    Route::get('/{id}', [ContactFormController::class, 'show'])->name('contact-forms.show');

    // Edit: отображение формы редактирования ресурса
    Route::get('/{id}/edit', [ContactFormController::class, 'edit'])->name('contact-forms.edit');

    // Update: обновление конкретного ресурса
    Route::put('/{id}', [ContactFormController::class, 'update'])->name('contact-forms.update');

    // Destroy: удаление конкретного ресурса
    Route::delete('/{id}', [ContactFormController::class, 'destroy'])->name('contact-forms.destroy');
});
