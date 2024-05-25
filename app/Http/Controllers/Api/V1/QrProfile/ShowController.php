<?php

namespace App\Http\Controllers\Api\V1\QrProfile;

use App\Exceptions\QrProfile\QrProfileNotFoundJsonException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QrProfileResource;
use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\CrudActionsServices\QrProfileService;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class ShowController extends Controller
{
    /**
     * @param FileService $fileService
     * @param QrProfileService $qrProfileService
     * @param QrProfileRepository $qrProfileRepository
     */
    public function __construct(
        readonly FileService $fileService,
        readonly QrProfileService $qrProfileService,
        readonly QrProfileRepository $qrProfileRepository
    )
    {
    }

    /**
     *  Display the specified resource.
     *
     * @param Request $request
     * @param $id
     * @return QrProfileResource|JsonResponse
     */
    public function show(Request $request, $id): QrProfileResource|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileRepository->getForEditModel((int) $id, true);

            if (empty($qrProfile)) {
                throw new QrProfileNotFoundJsonException();
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $qrProfile->setPhotoPath($this->fileService->processGetFrontPublicFilePath($qrProfile->getPhotoName(), $publicDirPath));
            $qrProfile->setVoiceMessagePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getVoiceMessageFileName(), $publicDirPath));
            $qrProfile->setQrCodePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getQrCodeFileName(), $publicDirPath));

            $qrProfileJsonResource = new QrProfileResource($qrProfile);

            return $qrProfileJsonResource->response()->setStatusCode(Response::HTTP_OK);
        } catch (QrProfileNotFoundJsonException $exception) {
            return $exception->render($request);
        } catch (Exception $exception) {
            Log::error('File: '.$exception->getFile().' ; Line: '.$exception->getLine().' ; Message: '.$exception->getMessage());

            return response()->json(data: ['error' => true, 'message' => __('Something went wrong.')], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
