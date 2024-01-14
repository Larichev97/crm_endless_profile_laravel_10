<?php

namespace App\Http\Controllers;

use App\Models\QrProfile;
use App\Repositories\City\CityRepository;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Country\CountryRepository;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Repositories\Setting\SettingRepository;
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
     * @param QrProfileRepository $qrProfileRepository
     */
    public function __construct(
        readonly FileService $fileService,
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
            $qrProfile = $this->qrProfileRepository->getForEditModel((int) $id, true);

            if (empty($qrProfile)) {
                abort(404);
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $photoPath = $this->fileService->processGetPublicFilePath($qrProfile->getPhotoName(), $publicDirPath);
            $voiceMessagePath = $this->fileService->processGetPublicFilePath($qrProfile->getVoiceMessageFileName(), $publicDirPath);

            return view('front_qr_profile.show',compact(['qrProfile', 'photoPath', 'voiceMessagePath',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
