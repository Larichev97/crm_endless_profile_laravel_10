<?php

namespace App\Http\Controllers;

use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\CrudActionsServices\QrProfileService;
use App\Services\FileService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

final class FrontQrProfileController extends Controller
{
    /**
     * @param FileService $fileService
     * @param QrProfileService $qrProfileService
     * @param QrProfileRepository $qrProfileRepository
     */
    public function __construct(
        readonly FileService $fileService,
        readonly QrProfileService $qrProfileService,
        readonly QrProfileRepository $qrProfileRepository,
    )
    {
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function show($id): Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($qrProfile)) {
                abort(404);
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $photoPath = $this->fileService->processGetPublicFilePath(fileName: $qrProfile->getPhotoName(), publicDirPath: $publicDirPath);
            $voiceMessagePath = $this->fileService->processGetPublicFilePath(fileName: $qrProfile->getVoiceMessageFileName(), publicDirPath: $publicDirPath);

            $sliderGalleryImagesData = $this->qrProfileService->processGetSliderGalleryImagesData(qrProfile: $qrProfile);

            return view('front_qr_profile.show',compact(['qrProfile', 'photoPath', 'voiceMessagePath', 'sliderGalleryImagesData']));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
