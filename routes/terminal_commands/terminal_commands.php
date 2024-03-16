<?php

use App\Http\Controllers\TerminalCommandController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('terminal-commands', TerminalCommandController::class);
*/

Route::prefix('terminal-commands')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [TerminalCommandController::class, 'index'])->name('terminal-commands.index');

    // Generate all QR-codes Images: команда для терминала
    Route::post('/generate-qr-codes', [TerminalCommandController::class, 'commandGenerateQrCodes'])->name('terminal-commands.generate-qr-codes');
});
