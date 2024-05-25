<?php

namespace App\Exceptions;

use App\Exceptions\QrProfile\QrProfileNotFoundJsonException;
use App\Exceptions\QrProfile\QrProfileUpdatedWithAnotherClientJsonException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // *** Custom exceptions for QrProfile Entity (API):
        $this->renderable(function (QrProfileNotFoundJsonException $e, Request $request) {
            return $e->render($request);
        });

        $this->renderable(function (QrProfileUpdatedWithAnotherClientJsonException $e, Request $request) {
            return $e->render($request);
        });
        // ***

    }
}
