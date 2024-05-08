<?php

namespace App\Http\Controllers\Admin\TerminalCommand;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class AdminTerminalCommandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|Application
    {
        try {
            return view('terminal_command.index');
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * @return RedirectResponse
     */
    public function commandGenerateQrCodes(): RedirectResponse
    {
        $exitCode = Artisan::call(command: 'qrs:generate');

        // Команда выполнена успешно:
        if ($exitCode === 0) {
            return redirect()->back()->with('success', 'Команда для генерирования изображений QR-кодов успешно выполнена!');
        }

        return redirect()->back()->with('error', 'Ошибка! Команда для генерирования изображений QR-кодов не выполнена!');
    }
}
