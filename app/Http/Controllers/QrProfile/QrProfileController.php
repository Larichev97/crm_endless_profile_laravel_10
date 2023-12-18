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
    protected FileService $fileService;
    protected ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->fileService = new FileService();
        $this->clientRepository = $clientRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $qrProfiles = QrProfile::query()->latest()->paginate(10);

        return view('qr_profile.index',compact('qrProfiles'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Application|Factory|View|\Illuminate\Foundation\Application
    {
        $idStatusNew = QrStatusEnum::NEW->value;

        $clientsList = $this->clientRepository->getClientsList();

        return view('qr_profile.create', compact(['idStatusNew', 'clientsList',]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QrProfileStoreRequest $qrProfileStoreRequest
     * @param QrProfileService $qrProfileService
     * @return RedirectResponse|JsonResponse
     */
    public function store(QrProfileStoreRequest $qrProfileStoreRequest, QrProfileService $qrProfileService): RedirectResponse|JsonResponse
    {
        try {
            $qrProfileStoreDTO = new QrProfileStoreDTO($qrProfileStoreRequest);

            $createQrProfile = $qrProfileService->processStore($qrProfileStoreDTO, $this->fileService);

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
            $qrProfile = QrProfile::query()->findOrFail($id);

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $photoPath = $this->fileService->processGetPublicFilePath((string) $qrProfile->photo_name, $publicDirPath);
            $voiceMessagePath = $this->fileService->processGetPublicFilePath((string) $qrProfile->voice_message_file_name, $publicDirPath);

            return view('qr_profile.show',compact(['qrProfile', 'photoPath', 'voiceMessagePath']));
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
            $qrProfile = QrProfile::query()->findOrFail($id);

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $photoPath = $this->fileService->processGetPublicFilePath((string) $qrProfile->photo_name, $publicDirPath);
            $voiceMessagePath = $this->fileService->processGetPublicFilePath((string) $qrProfile->voice_message_file_name, $publicDirPath);

            $statusesListData = QrStatusEnum::getStatusesList();
            $clientsList = $this->clientRepository->getClientsList();

            return view('qr_profile.edit',compact(['qrProfile', 'photoPath', 'voiceMessagePath', 'statusesListData', 'clientsList',]));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QrProfileUpdateRequest $qrProfileUpdateRequest
     * @param $id
     * @param QrProfileService $qrProfileService
     * @return RedirectResponse|JsonResponse
     */
    public function update(QrProfileUpdateRequest $qrProfileUpdateRequest, $id, QrProfileService $qrProfileService): RedirectResponse|JsonResponse
    {
        try {
            $qrProfileUpdateDTO = new QrProfileUpdateDTO($qrProfileUpdateRequest, (int) $id);

            $updateQrProfile = $qrProfileService->processUpdate($qrProfileUpdateDTO, $this->fileService);

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
     * @param QrProfileService $qrProfileService
     * @return RedirectResponse|JsonResponse
     */
    public function destroy($id, QrProfileService $qrProfileService): RedirectResponse|JsonResponse
    {
        try {
            $deleteQrProfile = $qrProfileService->processDestroy($id);

            if ($deleteQrProfile) {
                return redirect()->route('qrs.index')->with('success','QR-профиль успешно удалён.');
            }

            return back()->with('error', sprintf('Ошибка! QR-профиль #%s не удалён.', $id));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 401);
        }
    }
}
