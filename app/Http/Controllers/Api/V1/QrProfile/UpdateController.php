<?php

namespace App\Http\Controllers\Api\V1\QrProfile;

use App\Exceptions\QrProfile\QrProfileNotFoundJsonException;
use App\Exceptions\QrProfile\QrProfileUpdatedWithAnotherClientJsonException;
use App\Http\Controllers\Controller;
use App\DataTransferObjects\QrProfile\Api\QrProfileUpdateApiDTO;
use App\Http\Requests\QrProfile\Api\QrProfileUpdateApiRequest;
use App\Http\Resources\Api\V1\QrProfileResource;
use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\CrudActionsServices\Api\QrProfileApiService;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class UpdateController extends Controller
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
     *  Update the specified resource in storage.
     *
     * @param QrProfileUpdateApiRequest $qrProfileUpdateApiRequest
     * @param $id
     * @return QrProfileResource|JsonResponse
     */
    public function update(QrProfileUpdateApiRequest $qrProfileUpdateApiRequest, $id): QrProfileResource|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileApiService->processUpdate(dto: new QrProfileUpdateApiDTO(qrProfileUpdateApiRequest: $qrProfileUpdateApiRequest, qr_profile_id: (int) $id), repository: $this->qrProfileRepository);

            if ($qrProfile instanceof QrProfile && $qrProfile->getKey() > 0) {
                $publicDirPath = 'qr/'.$qrProfile->getKey();

                $qrProfile->setPhotoPath($this->fileService->processGetFrontPublicFilePath(fileName: $qrProfile->getPhotoName(), publicDirPath: $publicDirPath));
                $qrProfile->setVoiceMessagePath($this->fileService->processGetFrontPublicFilePath(fileName: $qrProfile->getVoiceMessageFileName(), publicDirPath: $publicDirPath));
                $qrProfile->setQrCodePath($this->fileService->processGetFrontPublicFilePath(fileName: $qrProfile->getQrCodeFileName(), publicDirPath: $publicDirPath));

                $qrProfileJsonResource = new QrProfileResource(resource: $qrProfile);

                return $qrProfileJsonResource->response()->setStatusCode(code: Response::HTTP_OK);
            }

            return response()->json(data: ['error' => true, 'message' => __('Unable to update a Qr Profile due to incorrect data.'),], status: Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (QrProfileNotFoundJsonException|QrProfileUpdatedWithAnotherClientJsonException $exception) {
            return $exception->render($qrProfileUpdateApiRequest);
        } catch (Exception $exception) {
            Log::error(message: 'File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());

            return response()->json(data: ['error' => true, 'message' => __('Something went wrong.')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
