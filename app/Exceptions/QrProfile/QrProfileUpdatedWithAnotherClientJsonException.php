<?php

namespace App\Exceptions\QrProfile;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QrProfileUpdatedWithAnotherClientJsonException extends Exception
{
    protected $code = 403;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json(
            [
                'error' => true,
                'message' => 'Access Error. You cannot modify this QR Profile.',
                'id' => (int) $request->route('id')
            ], Response::HTTP_FORBIDDEN
        );
    }
}
