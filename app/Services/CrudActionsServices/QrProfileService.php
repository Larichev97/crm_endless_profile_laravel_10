<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\DataTransferObjects\QrProfile\QrProfileImageGalleryStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileUpdateDTO;
use App\Models\QrProfile;
use App\Models\QrProfileImage;
use App\Repositories\CoreRepository;
use App\Repositories\QrProfile\QrProfileImageRepository;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Repositories\Setting\SettingRepository;
use App\Services\FileService;

final readonly class QrProfileService implements CoreCrudActionsInterface
{
    /**
     * @param FileService $fileService
     */
    public function __construct(
        private FileService $fileService
    )
    {
    }

    /**
     *  Создание записи о QR-профиле
     *
     * @param FormFieldsDtoInterface $dto
     * @return bool
     */
    public function processStore(FormFieldsDtoInterface $dto): bool
    {
        /** @var QrProfileStoreDTO $dto */

        $qrProfileModel = QrProfile::query()->create(attributes: $dto->getFormFieldsArray());

        if ($qrProfileModel) {
            /** @var QrProfile $qrProfileModel */

            $qrDirPath = 'qr/'.$qrProfileModel->getKey();

            $formFilesNamesArray['photo_file_name'] = $this->fileService->processUploadFile(file: $dto->photo_file, publicDirPath: $qrDirPath, oldFileName: '', newFileName: '', useOriginalFileName: false);
            $formFilesNamesArray['voice_message_file_name'] = $this->fileService->processUploadFile(file: $dto->voice_message_file, publicDirPath: $qrDirPath, oldFileName: '', newFileName: '', useOriginalFileName: true);

            return (bool) $qrProfileModel->update(attributes: $formFilesNamesArray);
        }

        return false;
    }

    /**
     *  Обновление записи о QR-профиле
     *
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return bool
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool
    {
        /** @var QrProfileUpdateDTO $dto */

        $qrProfileModel = $repository->getForEditModel(id: (int) $dto->id_qr, useCache: true);

        if (empty($qrProfileModel)) {
            return false;
        }

        $formDataArray = $dto->getFormFieldsArray();

        /** @var QrProfile $qrProfileModel */
        $qrDirPath = 'qr/'.$qrProfileModel->getKey();

        $formDataArray['photo_file_name'] = $this->fileService->processUploadFile(file: $dto->photo_file, publicDirPath: $qrDirPath, oldFileName: $dto->photo_file_name, newFileName: '', useOriginalFileName: true);
        $formDataArray['voice_message_file_name'] = $this->fileService->processUploadFile(file: $dto->voice_message_file, publicDirPath: $qrDirPath, oldFileName: $dto->voice_message_file_name, newFileName: '', useOriginalFileName: true);

        $updateQrProfile = $qrProfileModel->update(attributes: $formDataArray);

        return $updateQrProfile;
    }

    /**
     *  Скрытие записи о QR-профиле ("deleted_at" в таблице)
     *
     * @param $id
     * @param CoreRepository $repository
     * @return bool
     */
    public function processDestroy($id, CoreRepository $repository): bool
    {
        $qrProfileModel = $repository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($qrProfileModel)) {
            /** @var QrProfile $qrProfileModel */

            // Other logic...

            return (bool) $qrProfileModel->delete();
        }

        return false;
    }

    /**
     *  Создание QR-кода с ссылкой на конкретную страницу {$qrProfileUrl} + его сохранение в хранилище (через $fileService->processGenerateQrCodeFile)
     *  и сохранения названия файла {$qrCodeFileName.$qrCodeFileExtension} в таблицу модели QrProfile (`qr_profiles`) по конкретному {$id}
     *
     * @param $id
     * @param QrProfileRepository $qrProfileRepository
     * @param SettingRepository $settingRepository
     * @return bool
     */
    public function processGenerateQrCode($id, QrProfileRepository $qrProfileRepository, SettingRepository $settingRepository): bool
    {
        if (!empty($id) && is_numeric($id)) {
            $id = (int) $id;

            $qrProfileUrl = $settingRepository->getSettingValueByName(name: 'QR_PROFILE_BASE_URL', useCache: true);
            $qrCodeSize = $settingRepository->getSettingValueByName(name: 'QR_CODE_IMAGE_SIZE', useCache: true);

            $publicDirPath = 'qr/'.$id;

            $qrCodeFileName = 'qr_'.$id;
            $qrCodeFileExtension = 'png';

            if (!is_numeric($qrCodeSize) || (int) $qrCodeSize == 0) {
                $qrCodeSize = 400;
            }

            $qrProfileUrl .= $id;

            $generate = $this->fileService->processGenerateQrCodeFile(url: $qrProfileUrl, publicDirPath: $publicDirPath, qrCodeFileName: $qrCodeFileName, qrCodeFileExtension: $qrCodeFileExtension, qrCodeSize: (int) $qrCodeSize);

            if ($generate) {
                $qrProfileModel = $qrProfileRepository->getForEditModel(id: (int) $id, useCache: true);

                if (!empty($qrProfileModel)) {
                    /** @var QrProfile $qrProfileModel */
                    $updateQrProfile = $qrProfileModel->update(attributes: ['qr_code_file_name' => $qrCodeFileName.'.'.$qrCodeFileExtension]);

                    return $updateQrProfile;
                }
            }
        }

        return false;
    }

    /**
     *  Создание записей о изображениях в галереи QR-профиля
     *
     * @param QrProfileImageGalleryStoreDTO $qrProfileImageGalleryStoreDTO
     * @param QrProfileImageRepository $qrProfileImageRepository
     * @return bool
     */
    public function processStoreQrProfileGalleryPhotos(QrProfileImageGalleryStoreDTO $qrProfileImageGalleryStoreDTO, QrProfileImageRepository $qrProfileImageRepository): bool
    {
        $idQrProfile = $qrProfileImageGalleryStoreDTO->id_qr_profile;
        $galleryPhotos = $qrProfileImageGalleryStoreDTO->getGalleryPhotos();

        if ($idQrProfile > 0 && !empty($galleryPhotos)) {
            $publicDirPath = 'qr/'.$idQrProfile;

            foreach ($galleryPhotos as $key => $galleryPhoto) {
                $galleryPhotoName = trim($this->fileService->processUploadFile(file: $galleryPhoto, publicDirPath: $publicDirPath, oldFileName: '', newFileName: 'gallery_photo_'.time().$key, useOriginalFileName: false));

                if (!empty($galleryPhotoName)) {
                    $lastPositionNumber = (int) $qrProfileImageRepository->getLastPositionNumber(idQrProfile: $idQrProfile);

                    $lastPositionNumber++;

                    $qrProfileImageDataArray = [
                        'id_qr_profile' => $idQrProfile,
                        'image_name' => $galleryPhotoName,
                        'position' => $lastPositionNumber,
                        'is_active' => $qrProfileImageGalleryStoreDTO->is_active,
                    ];

                    QrProfileImage::query()->create(attributes: $qrProfileImageDataArray);

                    unset($lastPositionNumber);
                    unset($nextPositionNumber);
                }

                unset($galleryPhotoName);
            }

            return true;
        }

        return false;
    }

    /**
     *  Удаление записи об изображении из галереи QR-профиля
     *
     *  При успехе возвращается ID QR-профиля
     *
     * @param $id
     * @param QrProfileImageRepository $qrProfileImageRepository
     * @return int
     */
    public function processDestroyGalleryImage($id, QrProfileImageRepository $qrProfileImageRepository): int
    {
        $qrProfileImageModel = $qrProfileImageRepository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($qrProfileImageModel)) {
            /** @var QrProfileImage $qrProfileImageModel */

            $imageName = (string) $qrProfileImageModel->image_name;
            $idQrProfile = (int) $qrProfileImageModel->id_qr_profile;

            $deleteImage = (bool) $qrProfileImageModel->delete();

            if ($deleteImage) {
                // some logic after delete (update image positions...)

                $this->fileService->processDeleteOldFile(oldFileName: $imageName, publicDirPath: 'qr/'.$idQrProfile);

                return $idQrProfile;
            }
        }

        return 0;
    }

    /**
     *  Заполнение массива данными о фотографиях их галереи конкретного QR-профиля для слайдера
     *
     * @param QrProfile $qrProfile
     * @return array
     */
    public function processGetSliderGalleryImagesData(QrProfile $qrProfile): array
    {
        $sliderGalleryImagesData = [];

        $qrProfileGalleryImages = $qrProfile->qrProfileImages()->get();

        if (!empty($qrProfileGalleryImages)) {
            foreach ($qrProfileGalleryImages as $qrProfileGalleryImage) {
                /** @var QrProfileImage $qrProfileGalleryImage */

                if (!empty($qrProfileGalleryImage->image_name) && $qrProfileGalleryImage->is_active) {
                    $sliderGalleryImagesData[] = [
                        'imagePath' => $qrProfileGalleryImage->fullImagePath,
                        'imageAlt' => $qrProfileGalleryImage->image_name,
                    ];
                }
            }
        }

        return $sliderGalleryImagesData;
    }
}
