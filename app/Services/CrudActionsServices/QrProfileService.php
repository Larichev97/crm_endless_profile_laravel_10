<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\QrProfile\QrProfileStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileUpdateDTO;
use App\Models\QrProfile;
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

        $qrProfileModel = QrProfile::query()->create($formDataArray);

        if ($qrProfileModel) {
            /** @var QrProfile $qrProfileModel */
            $qrDirPath = 'qr/'.$qrProfileModel->getKey();

            $formFilesNamesArray['photo_file_name'] = $fileService->processUploadFile($qrProfileStoreDTO->photo_file, $qrDirPath, '', '');
            $formFilesNamesArray['voice_message_file_name'] = $fileService->processUploadFile($qrProfileStoreDTO->voice_message_file, $qrDirPath, '', '', true);

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
        $qrProfileModel = $qrProfileRepository->getForEditModel((int) $qrProfileUpdateDTO->id_qr, true);

        if (empty($qrProfileModel)) {
            return false;
        }

        $formDataArray = $qrProfileUpdateDTO->getFormFieldsArray();

        /** @var QrProfile $qrProfileModel */
        $qrDirPath = 'qr/'.$qrProfileModel->getKey();

        $formDataArray['photo_file_name'] = $fileService->processUploadFile($qrProfileUpdateDTO->photo_file, $qrDirPath, $qrProfileUpdateDTO->photo_file_name, '', true);
        $formDataArray['voice_message_file_name'] = $fileService->processUploadFile($qrProfileUpdateDTO->voice_message_file, $qrDirPath, $qrProfileUpdateDTO->voice_message_file_name, '', true);

        $updateQrProfile = $qrProfileModel->update($formDataArray);

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
        $qrProfileModel = $qrProfileRepository->getForEditModel((int) $id, true);

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

            $qrProfileUrl = $settingRepository->getSettingValueByName('QR_PROFILE_BASE_URL', true);
            $qrCodeSize = $settingRepository->getSettingValueByName('QR_CODE_IMAGE_SIZE', true);

            $publicDirPath = 'qr/'.$id;

            $qrCodeFileName = 'qr_code';
            $qrCodeFileExtension = 'png';

            if (!is_numeric($qrCodeSize) || (int) $qrCodeSize == 0) {
                $qrCodeSize = 400;
            }

            $generate = $fileService->processGenerateQrCodeFile($qrProfileUrl, $publicDirPath, $qrCodeFileName, $qrCodeFileExtension, (int) $qrCodeSize);

            if ($generate) {
                $qrProfileModel = $qrProfileRepository->getForEditModel((int) $id, true);

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
