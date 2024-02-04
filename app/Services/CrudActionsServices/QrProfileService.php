<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\QrProfile\QrProfileImageGalleryStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileUpdateDTO;
use App\Models\QrProfile;
use App\Models\QrProfileImage;
use App\Repositories\QrProfile\QrProfileImageRepository;
use App\Repositories\QrProfile\QrProfileRepository;
use App\Repositories\Setting\SettingRepository;
use App\Services\FileService;

final class QrProfileService
{
    /**
     *  Создание записи о QR-профиле
     *
     * @param QrProfileStoreDTO $qrProfileStoreDTO
     * @param FileService $fileService
     * @return bool
     */
    public function processStore(QrProfileStoreDTO $qrProfileStoreDTO, FileService $fileService): bool
    {
        $formDataArray = $qrProfileStoreDTO->getFormFieldsArray();

        $qrProfileModel = QrProfile::query()->create(attributes: $formDataArray);

        if ($qrProfileModel) {
            /** @var QrProfile $qrProfileModel */
            $qrDirPath = 'qr/'.$qrProfileModel->getKey();

            $formFilesNamesArray['photo_file_name'] = $fileService->processUploadFile(file: $qrProfileStoreDTO->photo_file, publicDirPath: $qrDirPath, oldFileName: '', newFileName: '', useOriginalFileName: false);
            $formFilesNamesArray['voice_message_file_name'] = $fileService->processUploadFile(file: $qrProfileStoreDTO->voice_message_file, publicDirPath: $qrDirPath, oldFileName: '', newFileName: '', useOriginalFileName: true);

            return (bool) $qrProfileModel->update(attributes: $formFilesNamesArray);
        }

        return false;
    }

    /**
     *  Обновление записи о QR-профиле
     *
     * @param QrProfileUpdateDTO $qrProfileUpdateDTO
     * @param FileService $fileService
     * @param QrProfileRepository $qrProfileRepository
     * @return bool
     */
    public function processUpdate(QrProfileUpdateDTO $qrProfileUpdateDTO, FileService $fileService, QrProfileRepository $qrProfileRepository): bool
    {
        $qrProfileModel = $qrProfileRepository->getForEditModel(id: (int) $qrProfileUpdateDTO->id_qr, useCache: true);

        if (empty($qrProfileModel)) {
            return false;
        }

        $formDataArray = $qrProfileUpdateDTO->getFormFieldsArray();

        /** @var QrProfile $qrProfileModel */
        $qrDirPath = 'qr/'.$qrProfileModel->getKey();

        $formDataArray['photo_file_name'] = $fileService->processUploadFile(file: $qrProfileUpdateDTO->photo_file, publicDirPath: $qrDirPath, oldFileName: $qrProfileUpdateDTO->photo_file_name, newFileName: '', useOriginalFileName: true);
        $formDataArray['voice_message_file_name'] = $fileService->processUploadFile(file: $qrProfileUpdateDTO->voice_message_file, publicDirPath: $qrDirPath, oldFileName: $qrProfileUpdateDTO->voice_message_file_name, newFileName: '', useOriginalFileName: true);

        $updateQrProfile = $qrProfileModel->update(attributes: $formDataArray);

        return $updateQrProfile;
    }

    /**
     *  Скрытие записи о QR-профиле ("deleted_at" в таблице)
     *
     * @param $id
     * @param QrProfileRepository $qrProfileRepository
     * @return bool
     */
    public function processDestroy($id, QrProfileRepository $qrProfileRepository): bool
    {
        $qrProfileModel = $qrProfileRepository->getForEditModel(id: (int) $id, useCache: true);

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
     * @param FileService $fileService
     * @return bool
     */
    public function processGenerateQrCode($id, QrProfileRepository $qrProfileRepository, SettingRepository $settingRepository, FileService $fileService): bool
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

            $generate = $fileService->processGenerateQrCodeFile(url: $qrProfileUrl, publicDirPath: $publicDirPath, qrCodeFileName: $qrCodeFileName, qrCodeFileExtension: $qrCodeFileExtension, qrCodeSize: (int) $qrCodeSize);

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
     * @param FileService $fileService
     * @return bool
     */
    public function processStoreQrProfileGalleryPhotos(QrProfileImageGalleryStoreDTO $qrProfileImageGalleryStoreDTO, QrProfileImageRepository $qrProfileImageRepository, FileService $fileService): bool
    {
        $idQrProfile = $qrProfileImageGalleryStoreDTO->id_qr_profile;
        $galleryPhotos = $qrProfileImageGalleryStoreDTO->getGalleryPhotos();

        if ($idQrProfile > 0 && !empty($galleryPhotos)) {
            $publicDirPath = 'qr/'.$idQrProfile;

            foreach ($galleryPhotos as $galleryPhoto) {
                $galleryPhotoName = trim($fileService->processUploadFile(file: $galleryPhoto, publicDirPath: $publicDirPath, oldFileName: '', newFileName: 'gallery_photo_'.time(), useOriginalFileName: false));

                if (!empty($galleryPhotoName)) {
                    // toDo FIX POSITION BEFORE SAVE IMAGE:
                    $lastPositionNumber = (int) $qrProfileImageRepository->getLastPositionNumber($idQrProfile);
                    $nextPositionNumber = $lastPositionNumber++;

                    $qrProfileImageDataArray = [
                        'id_qr_profile' => $idQrProfile,
                        'image_name' => $galleryPhotoName,
                        'position' => $nextPositionNumber,
                        'is_active' => 1,
                    ];

                    QrProfileImage::query()->create(attributes: $qrProfileImageDataArray);

                    unset($galleryPhotoName);
                    unset($lastPositionNumber);
                    unset($nextPositionNumber);
                }
            }

            return true;
        }

        return false;
    }

    /**
     *  Удаление записи об изображении из галереи QR-профиля
     *
     * @param $id
     * @param QrProfileImageRepository $qrProfileImageRepository
     * @param FileService $fileService
     * @return bool
     */
    public function processDestroyGalleryImage($id, QrProfileImageRepository $qrProfileImageRepository, FileService $fileService): bool
    {
        $qrProfileImageModel = $qrProfileImageRepository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($qrProfileImageModel)) {
            /** @var QrProfileImage $qrProfileImageModel */

            $imageName = (string) $qrProfileImageModel->image_name;
            $idQrProfile = (int) $qrProfileImageModel->id_qr_profile;

            $deleteImage = (bool) $qrProfileImageModel->delete();

            if ($deleteImage) {
                // some logic after delete (update image positions...)

                $fileService->processDeleteOldFile($imageName, 'qr/'.$idQrProfile);
            }

            return $deleteImage;
        }

        return false;
    }
}
