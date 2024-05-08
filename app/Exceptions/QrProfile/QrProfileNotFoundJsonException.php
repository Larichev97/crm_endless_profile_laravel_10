<?php

namespace App\Exceptions\QrProfile;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QrProfileNotFoundJsonException extends Exception
{
    protected $code = 404;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json(
            [
                'error' => true,
                'message' => 'QR Profile not found.',
                'id' => (int) $request->route('id')
            ], Response::HTTP_NOT_FOUND
        );
    }
}
