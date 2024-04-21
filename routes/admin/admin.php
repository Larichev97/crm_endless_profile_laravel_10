<?php

use App\Http\Controllers\Admin\Auth\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // Главная:
    Route::get('/', function () { return redirect('/dashboard'); })->middleware('auth');
    Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->middleware('auth')->name('admin.dashboard');

    // Авторизация (laravel/ui):
    require_once 'auth/auth.php';

    // User entity:
    require_once 'user-profile/user-profile.php';

    // Country entity:
    require_once 'countries/countries.php';

    // City entity:
    require_once 'cities/cities.php';

    // Client entity:
    require_once 'clients/clients.php';

    // QrProfile entity:
    require_once 'qrs/qrs.php';

    // Setting entity:
    require_once 'settings/settings.php';

    // ContactForm entity:
    require_once 'contact-forms/contact-forms.php';

    // Terminal Command entity:
    require_once 'terminal-commands/terminal-commands.php';
});
