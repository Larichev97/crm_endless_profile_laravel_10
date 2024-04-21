<?php

use App\Http\Controllers\Admin\TerminalCommand\AdminTerminalCommandController;
use Illuminate\Support\Facades\Route;

/*
Route::resource('terminal-commands', AdminTerminalCommandController::class);
*/

Route::prefix('terminal-commands')->group(function () {
    // Index: вывод списка ресурсов
    Route::get('/', [AdminTerminalCommandController::class, 'index'])->name('admin.terminal-commands.index');

    // Generate all QR-codes Images: команда для терминала
    Route::post('/generate-qr-codes', [AdminTerminalCommandController::class, 'commandGenerateQrCodes'])->name('admin.terminal-commands.generate-qr-codes');
});
