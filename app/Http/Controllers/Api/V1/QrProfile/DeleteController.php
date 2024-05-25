<?php

namespace App\Http\Controllers\Api\V1\QrProfile;

use App\Exceptions\QrProfile\QrProfileNotFoundJsonException;
use App\Http\Controllers\Controller;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\CrudActionsServices\Api\QrProfileApiService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class DeleteController extends Controller
{
    /**
     * @param QrProfileApiService $qrProfileApiService
     * @param QrProfileRepository $qrProfileRepository
     */
    public function __construct(
        readonly QrProfileApiService $qrProfileApiService,
        readonly QrProfileRepository $qrProfileRepository
    )
    {
    }

    /**
     *  Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            if (is_numeric($id)) {
                $deleteProduct = $this->qrProfileApiService->processDestroy(id: (int) $id, repository: $this->qrProfileRepository);

                if ($deleteProduct) {
                    return response()->json(data: ['error' => false, 'message' => __('QR Profile deleted successfully.'),], status: Response::HTTP_NO_CONTENT);
                }
            }

            return response()->json(data: ['error' => true, 'message' => __('Unable to delete a QR Profile due to incorrect data.'),], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (QrProfileNotFoundJsonException $exception) {
            return $exception->render($request);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());

            return response()->json(data: ['error' => true, 'message' => __('Something went wrong.')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
