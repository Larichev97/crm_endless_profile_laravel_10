<?php

namespace App\Http\Controllers\Admin\QrProfile;

use App\DataTransferObjects\QrProfile\QrProfileImageGalleryStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileUpdateDTO;
use App\Enums\QrStatusEnum;
use App\Exceptions\QrProfile\QrProfileNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\QrProfile\QrProfileImageGalleryStoreRequest;
use App\Http\Requests\QrProfile\QrProfileStoreRequest;
use App\Http\Requests\QrProfile\QrProfileUpdateRequest;
use App\Models\QrProfile;
use App\Repositories\City\CityRepository;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Country\CountryRepository;
use App\Repositories\QrProfile\QrProfileImageRepository;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Repositories\Setting\SettingRepository;
use App\Services\CrudActionsServices\QrProfileService;
use App\Services\FileService;
use App\Services\FilterTableService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AdminQrProfileController extends Controller
{
    /**
     * @param FileService $fileService
     * @param QrProfileService $qrProfileService
     * @param ClientRepository $clientRepository
     * @param CountryRepository $countryRepository
     * @param CityRepository $cityRepository
     * @param QrProfileRepository $qrProfileRepository
     * @param SettingRepository $settingRepository
     */
    public function __construct(
        readonly FileService $fileService,
        readonly QrProfileService $qrProfileService,
        readonly ClientRepository $clientRepository,
        readonly CountryRepository $countryRepository,
        readonly CityRepository $cityRepository,
        readonly QrProfileRepository $qrProfileRepository,
        readonly SettingRepository $settingRepository,
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param FilterTableService $filterTableService
     * @return \Illuminate\Foundation\Application|View|Factory|Application
     */
    public function index(Request $request, FilterTableService $filterTableService): \Illuminate\Foundation\Application|View|Factory|Application
    {
        try {
            $page = (int) $request->get('page', 1);
            $sortBy = $request->get('sort_by', 'id');
            $sortWay = $request->get('sort_way', 'desc');

            $filterFieldsArray = $filterTableService->processPrepareFilterFieldsArray(allFieldsData: $request->all());
            $filterFieldsObject = $filterTableService->processConvertFilterFieldsToObject($filterFieldsArray);

            $qrProfiles = $this->qrProfileRepository->getAllWithPaginate(perPage: 10, page: $page, orderBy: $sortBy, orderWay: $sortWay);
            $displayedFields = $this->qrProfileRepository->getDisplayedFieldsOnIndexPage();

            $clientsListData = $this->clientRepository->getForDropdownList(fieldId: 'id', fieldName: 'CONCAT(lastname, " ", firstname, " ", surname) AS name', useCache: true);
            $statusesListData = QrStatusEnum::getStatusesList();
            $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

            return view('qr_profile.index',compact(['qrProfiles', 'displayedFields', 'filterFieldsObject', 'clientsListData', 'statusesListData', 'countriesListData', 'sortBy', 'sortWay',]));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
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

        $clientsListData = $this->clientRepository->getForDropdownList(fieldId: 'id',fieldName: 'CONCAT(lastname, " ", firstname, " ", surname) AS name', useCache: true);
        $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);
        $citiesListData = $this->cityRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

        return view('qr_profile.create', compact(['idStatusNew', 'clientsListData', 'countriesListData', 'citiesListData',]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QrProfileStoreRequest $qrProfileStoreRequest
     * @return View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
     */
    public function store(QrProfileStoreRequest $qrProfileStoreRequest): View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
    {
        try {
            $createQrProfile = $this->qrProfileService->processStore(dto: new QrProfileStoreDTO($qrProfileStoreRequest));

            if ($createQrProfile) {
                return redirect()->route('admin.qrs.index')->with(key: 'success', value: 'QR-профиль успешно создан.');
            }

            return back()->with(key: 'error', value: 'Ошибка! QR-профиль не создан.')->withInput();
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function show($id): Application|Factory|View|\Illuminate\Foundation\Application
    {
        try {
            $qrProfile = $this->qrProfileRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($qrProfile)) {
                throw new QrProfileNotFoundException(sprintf('QR-профиль #%s не найден.', $id));
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $photoPath = $this->fileService->processGetPublicFilePath(fileName: $qrProfile->getPhotoName(), publicDirPath: $publicDirPath);
            $voiceMessagePath = $this->fileService->processGetPublicFilePath(fileName: $qrProfile->getVoiceMessageFileName(), publicDirPath: $publicDirPath);
            $qrCodePath = $this->fileService->processGetPublicFilePath(fileName: $qrProfile->getQrCodeFileName(), publicDirPath: $publicDirPath);

            return view('qr_profile.show',compact(['qrProfile', 'photoPath', 'voiceMessagePath', 'qrCodePath']));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function edit($id): Application|Factory|View|\Illuminate\Foundation\Application
    {
        try {
            $qrProfile = $this->qrProfileRepository->getForEditModel(id: (int) $id, useCache: true);

            if (empty($qrProfile)) {
                throw new QrProfileNotFoundException(sprintf('QR-профиль #%s не найден.', $id));
            }

            /** @var QrProfile $qrProfile */
            $publicDirPath = 'qr/'.$qrProfile->getKey();

            $photoPath = $this->fileService->processGetPublicFilePath(fileName: $qrProfile->getPhotoName(), publicDirPath: $publicDirPath);
            $voiceMessagePath = $this->fileService->processGetPublicFilePath(fileName: $qrProfile->getVoiceMessageFileName(), publicDirPath: $publicDirPath);

            $statusesListData = QrStatusEnum::getStatusesList();
            $clientsListData = $this->clientRepository->getForDropdownList(fieldId: 'id',fieldName: 'CONCAT(lastname, " ", firstname, " ", surname) AS name', useCache: true);
            $countriesListData = $this->countryRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);
            $citiesListData = $this->cityRepository->getForDropdownList(fieldId: 'id', fieldName: 'name', useCache: true);

            return view('qr_profile.edit',compact(['qrProfile', 'photoPath', 'voiceMessagePath', 'statusesListData', 'clientsListData', 'countriesListData', 'citiesListData',]));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QrProfileUpdateRequest $qrProfileUpdateRequest
     * @param $id
     * @return View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
     */
    public function update(QrProfileUpdateRequest $qrProfileUpdateRequest, $id): View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
    {
        try {
            $updateQrProfile = $this->qrProfileService->processUpdate(dto: new QrProfileUpdateDTO(qrProfileUpdateRequest: $qrProfileUpdateRequest, id_qr: (int) $id), repository: $this->qrProfileRepository);

            if ($updateQrProfile) {
                return redirect()->route('admin.qrs.index')->with(key: 'success', value: sprintf('Данные QR-профиля #%s успешно обновлены.', $id));
            }

            return back()->with(key: 'error', value: 'Ошибка! Данные QR-профиля не обновлены.')->withInput();
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function destroy($id): Factory|View|\Illuminate\Foundation\Application|Application|RedirectResponse
    {
        try {
            $deleteQrProfile = $this->qrProfileService->processDestroy(id: $id, repository: $this->qrProfileRepository);

            if ($deleteQrProfile) {
                return redirect()->route('admin.qrs.index')->with(key: 'success', value: sprintf('QR-профиль #%s успешно удалён.', $id));
            }

            return back()->with(key: 'error', value: sprintf('Ошибка! QR-профиль #%s не удалён.', $id));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     * @param $id
     * @return Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
     */
    public function generateQrCodeImage($id): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {
            $generateQrCode = $this->qrProfileService->processGenerateQrCode(id: $id, qrProfileRepository: $this->qrProfileRepository, settingRepository: $this->settingRepository);

            if ($generateQrCode) {
                return redirect()->route('admin.qrs.show', $id)->with(key: 'success', value: sprintf('Изображение QR-кода для профиля #%s успешно создано.', $id));
            }

            return redirect()->route('admin.qrs.show', $id)->with(key: 'error', value: sprintf('Ошибка! Изображение QR-кода для профиля #%s не создано.', $id));
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     *  Добавление фотографий для галереи QR-профиля
     *
     * @param $idQrProfile
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function createGalleryImages($idQrProfile): Application|Factory|View|\Illuminate\Foundation\Application
    {
        return view('qr_profile_images_gallery.create', compact(['idQrProfile',]));
    }

    /**
     *  Создание фотографий для галереи QR-профиля
     *
     * @param QrProfileImageGalleryStoreRequest $qrProfileImageGalleryStoreRequest
     * @param QrProfileImageRepository $qrProfileImageRepository
     * @return View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
     */
    public function storeGalleryImages(QrProfileImageGalleryStoreRequest $qrProfileImageGalleryStoreRequest, QrProfileImageRepository $qrProfileImageRepository): View|\Illuminate\Foundation\Application|Factory|Application|RedirectResponse
    {
        try {
            $qrProfileImageGalleryStoreDTO = new QrProfileImageGalleryStoreDTO($qrProfileImageGalleryStoreRequest);

            $createQrProfileImagesGallery = $this->qrProfileService->processStoreQrProfileGalleryPhotos(qrProfileImageGalleryStoreDTO: $qrProfileImageGalleryStoreDTO, qrProfileImageRepository: $qrProfileImageRepository);

            if ($createQrProfileImagesGallery) {
                return redirect()->route('admin.qrs.show', ['id' => $qrProfileImageGalleryStoreDTO->id_qr_profile])->with(key: 'success', value: 'Фотографии успешно добавлены в галерею QR-профиля.');
            }

            return back()->with(key: 'error', value: 'Ошибка! Фотографии не добавлены в галерею QR-профиля.')->withInput();
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }

    /**
     *  Удаление фотографии из галереи QR-профиля
     *
     * @param $id
     * @param QrProfileImageRepository $qrProfileImageRepository
     * @return Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
     */
    public function destroyGalleryImage($id, QrProfileImageRepository $qrProfileImageRepository): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {
            $idQrProfileDeletedGalleryImage = $this->qrProfileService->processDestroyGalleryImage(id: $id, qrProfileImageRepository: $qrProfileImageRepository);

            if ($idQrProfileDeletedGalleryImage > 0) {
                return redirect()->route('admin.qrs.show', $idQrProfileDeletedGalleryImage)->with(key: 'success', value: 'Изображение удалено из галереи QR-профиля.');
            }

            return back()->with(key: 'error', value: 'Ошибка! Изображение не удалено из галереи QR-профиля');
        } catch (Exception $exception) {
            return view(view: 'pages.exception', data: ['error_message' => $exception->getMessage(), 'error_code' => $exception->getCode()]);
        }
    }
}
