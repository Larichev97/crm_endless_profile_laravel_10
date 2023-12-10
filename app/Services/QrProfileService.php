<?php

namespace App\Services;

use App\DataTransferObjects\QrProfile\QrProfileStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileUpdateDTO;
use App\Models\QrProfile;

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

        $qrProfileModel = QrProfile::query()->create($formDataArray);

        if ($qrProfileModel) {
            /** @var QrProfile $qrProfileModel */
            $qrDirPath = 'qr/'.$qrProfileModel->getKey();

            $formFilesNamesArray['photo_file_name'] = $fileService->processUploadFile($qrProfileStoreDTO->photo_file, $qrDirPath);
            $formFilesNamesArray['voice_message_file_name'] = $fileService->processUploadFile($qrProfileStoreDTO->voice_message_file, $qrDirPath);

            return (bool) $qrProfileModel->update($formFilesNamesArray);
        }

        return false;
    }

    /**
     *  Обновление записи о QR-профиле
     *
     * @param QrProfileUpdateDTO $qrProfileUpdateDTO
     * @param FileService $fileService
     * @return bool
     */
    public function processUpdate(QrProfileUpdateDTO $qrProfileUpdateDTO, FileService $fileService): bool
    {
        $qrProfileModel = QrProfile::query()->findOrFail($qrProfileUpdateDTO->id_qr);

        $formDataArray = $qrProfileUpdateDTO->getFormFieldsArray();

        /** @var QrProfile $qrProfileModel */
        $qrDirPath = 'qr/'.$qrProfileModel->getKey();

        $formDataArray['photo_file_name'] = $fileService->processUploadFile($qrProfileUpdateDTO->photo_file, $qrDirPath);
        $formDataArray['voice_message_file_name'] = $fileService->processUploadFile($qrProfileUpdateDTO->voice_message_file, $qrDirPath);

        $updateQrProfile = $qrProfileModel->update($formDataArray);

        if ($updateQrProfile) {
            $fileService->processDeleteOldFile($qrProfileUpdateDTO->photo_file_name, $qrDirPath);
            $fileService->processDeleteOldFile($qrProfileUpdateDTO->voice_message_file_name, $qrDirPath);
        }

        return $updateQrProfile;
    }

    /**
     *  Скрытие записи о QR-профиле ("deleted_at" в таблице)
     *
     * @param $id
     * @return bool
     */
    public function processDestroy($id): bool
    {
        $qrProfile = QrProfile::query()->findOrFail($id);

        // Other logic...

        return (bool) $qrProfile->delete();
    }
}
