<?php

namespace App\Services;

use App\DataTransferObjects\QrProfile\QrProfileStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileUpdateDTO;
use App\Models\QrProfile;
use App\Repositories\QrProfile\QrProfileRepository;

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
     * @param QrProfileRepository $qrProfileRepository
     * @return bool
     */
    public function processUpdate(QrProfileUpdateDTO $qrProfileUpdateDTO, FileService $fileService, QrProfileRepository $qrProfileRepository): bool
    {
        $qrProfileModel = $qrProfileRepository->getForEditModel($qrProfileUpdateDTO->id_qr);

        if (empty($qrProfileModel)) {
            return false;
        }

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
     * @param QrProfileRepository $qrProfileRepository
     * @return bool
     */
    public function processDestroy($id, QrProfileRepository $qrProfileRepository): bool
    {
        $qrProfileModel = $qrProfileRepository->getForEditModel($id);

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
     * @param FileService $fileService
     * @return bool
     */
    public function processGenerateQrCode($id, QrProfileRepository $qrProfileRepository, FileService $fileService): bool
    {
        if (!empty($id) && is_numeric($id)) {
            $id = (int) $id;

            $qrProfileUrl = route('qrs.show', $id);

            $publicDirPath = 'qr/'.$id;

            $qrCodeFileName = 'qr_code';
            $qrCodeFileExtension = 'png';
            $qrCodeSize = 400;

            $generate = $fileService->processGenerateQrCodeFile($qrProfileUrl, $publicDirPath, $qrCodeFileName, $qrCodeFileExtension, $qrCodeSize);

            if ($generate) {
                $qrProfileModel = $qrProfileRepository->getForEditModel($id);

                if (!empty($qrProfileModel)) {
                    /** @var QrProfile $qrProfileModel */
                    $updateQrProfile = $qrProfileModel->update(['qr_code_file_name' => $qrCodeFileName.'.'.$qrCodeFileExtension]);

                    return $updateQrProfile;
                }
            }
        }

        return false;
    }
}
