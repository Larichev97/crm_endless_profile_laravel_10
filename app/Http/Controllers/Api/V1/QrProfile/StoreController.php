<?php

namespace App\Http\Controllers\Api\V1\QrProfile;

use App\DataTransferObjects\QrProfile\Api\QrProfileStoreApiDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\QrProfile\Api\QrProfileStoreApiRequest;
use App\Http\Resources\Api\V1\QrProfileResource;
use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\CrudActionsServices\Api\QrProfileApiService;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class StoreController extends Controller
{
    /**
     * @param FileService $fileService
     * @param QrProfileApiService $qrProfileApiService
     * @param QrProfileRepository $qrProfileRepository
     */
    public function __construct(
        readonly FileService $fileService,
        readonly QrProfileApiService $qrProfileApiService,
        readonly QrProfileRepository $qrProfileRepository
    )
    {
    }

    /**
     *  Store a newly created resource in storage.
     *
     * @param QrProfileStoreApiRequest $qrProfileStoreApiRequest
     * @return QrProfileResource|JsonResponse
     */
    public function store(QrProfileStoreApiRequest $qrProfileStoreApiRequest): QrProfileResource|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileApiService->processStore(dto: new QrProfileStoreApiDTO($qrProfileStoreApiRequest));

            if ($qrProfile instanceof QrProfile && $qrProfile->getKey() > 0) {
                $publicDirPath = 'qr/'.$qrProfile->getKey();

                $qrProfile->setPhotoPath($this->fileService->processGetFrontPublicFilePath($qrProfile->getPhotoName(), $publicDirPath));
                $qrProfile->setVoiceMessagePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getVoiceMessageFileName(), $publicDirPath));
                $qrProfile->setQrCodePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getQrCodeFileName(), $publicDirPath));

                $qrProfileJsonResource = new QrProfileResource($qrProfile);

                return $qrProfileJsonResource->response()->setStatusCode(Response::HTTP_CREATED);
            }

            return response()->json(data: ['error' => true, 'message' => __('Unable to create a Qr Profile due to incorrect data.'),], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());

            return response()->json(data: ['error' => true, 'message' => __('Something went wrong.')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
