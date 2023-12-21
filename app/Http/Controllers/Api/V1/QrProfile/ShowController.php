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
use Illuminate\Support\Facades\Storage;

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
            $qrProfile = $this->qrProfileRepository->getForEditModel($id);

            if (empty($qrProfile)) {
                abort(404);
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $qrProfile->setPhotoPath($this->fileService->processGetFrontPublicFilePath((string) $qrProfile->photo_name, $publicDirPath));
            $qrProfile->setVoiceMessagePath($this->fileService->processGetFrontPublicFilePath((string) $qrProfile->voice_message_file_name, $publicDirPath));
            $qrProfile->setQrCodePath($this->fileService->processGetFrontPublicFilePath((string) $qrProfile->qr_code_file_name, $publicDirPath));

            return new QrProfileResource($qrProfile);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
