<?php

use App\Http\Controllers\FrontQrProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Admin panel group (/admin/...):
require_once 'admin/admin.php';

// Front #####################################################################################################

// Главная:
Route::get('/', function () { return redirect()->route('admin.dashboard'); });

// QrProfile (view profile via QR-Code):
Route::get('qr-profile/{id}', [FrontQrProfileController::class, 'show'])->name('front-qrs.show');

// ContactForm entity (via AJAX):
require_once 'contact-forms/ajax-contact-forms.php';
