<?php

use App\Http\Controllers\ContactForm\ContactFormController;
use Illuminate\Support\Facades\Route;

Route::prefix('contact-forms')->group(function () {
    // Ajax Store: сохранение нового ресурса через AJAX на фронт странице
    Route::post('/', [ContactFormController::class, 'ajaxStore'])->name('contact-forms.ajax-store');
});
