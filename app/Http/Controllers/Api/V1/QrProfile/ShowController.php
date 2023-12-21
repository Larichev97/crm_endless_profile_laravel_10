<?php

namespace App\Http\Controllers\Api\V1\QrProfile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QrProfileResource;
use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\FileService;
use App\Services\QrProfileService;
use Exception;
use Illuminate\Http\JsonResponse;

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
     * Display the specified resource.
     *
     * @param $id
     * @return QrProfileResource|JsonResponse
     */
    public function show($id): QrProfileResource|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileRepository->getForEditModel((int) $id, true);

            if (empty($qrProfile)) {
                abort(404);
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $qrProfile->setPhotoPath($this->fileService->processGetFrontPublicFilePath($qrProfile->getPhotoName(), $publicDirPath));
            $qrProfile->setVoiceMessagePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getVoiceMessageFileName(), $publicDirPath));
            $qrProfile->setQrCodePath($this->fileService->processGetFrontPublicFilePath($qrProfile->getQrCodeFileName(), $publicDirPath));

            return new QrProfileResource($qrProfile);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
