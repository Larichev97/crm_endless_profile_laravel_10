<?php

namespace App\Http\Controllers\QrProfile;

use App\DataTransferObjects\QrProfile\QrProfileStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileUpdateDTO;
use App\Enums\QrStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\QrProfile\QrProfileStoreRequest;
use App\Http\Requests\QrProfile\QrProfileUpdateRequest;
use App\Models\QrProfile;
use App\Repositories\Client\ClientRepository;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Services\FileService;
use App\Services\QrProfileService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

final class QrProfileController extends Controller
{
    /**
     * @param FileService $fileService
     * @param QrProfileService $qrProfileService
     * @param ClientRepository $clientRepository
     * @param QrProfileRepository $qrProfileRepository
     */
    public function __construct(
        readonly FileService $fileService,
        readonly QrProfileService $qrProfileService,
        readonly ClientRepository $clientRepository,
        readonly QrProfileRepository $qrProfileRepository
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
     */
    public function index(): \Illuminate\Foundation\Application|View|Factory|JsonResponse|Application
    {
        try {
            $qrProfiles = $this->qrProfileRepository->getAllWithPaginate(10);

            return view('qr_profile.index',compact('qrProfiles'))->with('i', (request()->input('page', 1) - 1) * 5);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $idStatusNew = QrStatusEnum::NEW->value;

        $clientsListData = $this->clientRepository->getForDropdownList('id','CONCAT(lastname, " ", firstname, " ", surname) AS name');

        return view('qr_profile.create', compact(['idStatusNew', 'clientsListData',]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QrProfileStoreRequest $qrProfileStoreRequest
     * @return RedirectResponse|JsonResponse
     */
    public function store(QrProfileStoreRequest $qrProfileStoreRequest): RedirectResponse|JsonResponse
    {
        try {
            $qrProfileStoreDTO = new QrProfileStoreDTO($qrProfileStoreRequest);

            $createQrProfile = $this->qrProfileService->processStore($qrProfileStoreDTO, $this->fileService);

            if ($createQrProfile) {
                return redirect()->route('qrs.index')->with('success', 'QR-профиль успешно создан.');
            }

            return back()->with('error', 'Ошибка! QR-профиль не создан.')->withInput();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
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
            $qrProfile = $this->qrProfileRepository->getForEditModel($id);

            if (empty($qrProfile)) {
                abort(404);
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $photoPath = $this->fileService->processGetPublicFilePath((string) $qrProfile->photo_name, $publicDirPath);
            $voiceMessagePath = $this->fileService->processGetPublicFilePath((string) $qrProfile->voice_message_file_name, $publicDirPath);
            $qrCodePath = $this->fileService->processGetPublicFilePath((string) $qrProfile->qr_code_file_name, $publicDirPath);

            return view('qr_profile.show',compact(['qrProfile', 'photoPath', 'voiceMessagePath', 'qrCodePath']));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
     */
    public function edit($id): Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse
    {
        try {
            $qrProfile = $this->qrProfileRepository->getForEditModel($id);

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $photoPath = $this->fileService->processGetPublicFilePath((string) $qrProfile->photo_name, $publicDirPath);
            $voiceMessagePath = $this->fileService->processGetPublicFilePath((string) $qrProfile->voice_message_file_name, $publicDirPath);

            $statusesListData = QrStatusEnum::getStatusesList();
            $clientsListData = $this->clientRepository->getForDropdownList('id','CONCAT(lastname, " ", firstname, " ", surname) AS name');

            return view('qr_profile.edit',compact(['qrProfile', 'photoPath', 'voiceMessagePath', 'statusesListData', 'clientsListData',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QrProfileUpdateRequest $qrProfileUpdateRequest
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function update(QrProfileUpdateRequest $qrProfileUpdateRequest, $id): RedirectResponse|JsonResponse
    {
        try {
            $qrProfileUpdateDTO = new QrProfileUpdateDTO($qrProfileUpdateRequest, (int) $id);

            $updateQrProfile = $this->qrProfileService->processUpdate($qrProfileUpdateDTO, $this->fileService, $this->qrProfileRepository);

            if ($updateQrProfile) {
                return redirect()->route('qrs.index')->with('success', sprintf('Данные QR-профиля #%s успешно обновлены.', $id));
            }

            return back()->with('error','Ошибка! Данные QR-профиля не обновлены.')->withInput();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse|JsonResponse
     */
    public function destroy($id): RedirectResponse|JsonResponse
    {
        try {
            $deleteQrProfile = $this->qrProfileService->processDestroy($id, $this->qrProfileRepository);

            if ($deleteQrProfile) {
                return redirect()->route('qrs.index')->with('success', sprintf('QR-профиль #%s успешно удалён.', $id));
            }

            return back()->with('error', sprintf('Ошибка! QR-профиль #%s не удалён.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function generateQrCodeImage($id): JsonResponse|RedirectResponse
    {
        try {
            $generateQrCode = $this->qrProfileService->processGenerateQrCode($id, $this->qrProfileRepository, $this->fileService);

            if ($generateQrCode) {
                return redirect()->route('qrs.show', $id)->with('success', sprintf('Изображение QR-кода для профиля #%s успешно создано.', $id));
            }

            return redirect()->route('qrs.show', $id)->with('error', sprintf('Ошибка! Изображение QR-кода для профиля #%s не создано.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
