<?php

namespace App\Services\CrudActionsServices\Api;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\DataTransferObjects\QrProfile\Api\QrProfileStoreApiDTO;
use App\DataTransferObjects\QrProfile\Api\QrProfileUpdateApiDTO;
use App\Exceptions\QrProfile\QrProfileNotFoundJsonException;
use App\Exceptions\QrProfile\QrProfileUpdatedWithAnotherClientJsonException;
use App\Models\QrProfile;
use App\Repositories\CoreRepository;
use App\Services\CrudActionsServices\CoreCrudActionsApiInterface;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Model;

final readonly class QrProfileApiService implements CoreCrudActionsApiInterface
{
    /**
     * @var FileService
     */
    private FileService $fileService;

    public function __construct()
    {
        $this->fileService = new FileService();
    }

    /**
     *  Создание записи о QR-профиле
     *
     * @param FormFieldsDtoInterface $dto
     * @return Model|false
     */
    public function processStore(FormFieldsDtoInterface $dto): Model|false
    {
        /** @var QrProfileStoreApiDTO $dto */

        $qrProfileModel = QrProfile::query()->create(attributes: $dto->getFormFieldsArray());

        if ($qrProfileModel) {
            /** @var QrProfile $qrProfileModel */

            $qrDirPath = 'qr/'.$qrProfileModel->getKey();

            $formFilesNamesArray['photo_file_name'] = $this->fileService->processUploadFile(file: $dto->photo_file, publicDirPath: $qrDirPath, oldFileName: '', newFileName: '', useOriginalFileName: false);
            $formFilesNamesArray['voice_message_file_name'] = $this->fileService->processUploadFile(file: $dto->voice_message_file, publicDirPath: $qrDirPath, oldFileName: '', newFileName: '', useOriginalFileName: true);

            $updateQrProfile = $qrProfileModel->update(attributes: $formFilesNamesArray);

            if ($updateQrProfile) {
                return $qrProfileModel;
            }
        }

        return false;
    }

    /**
     *  Обновление записи о QR-профиле
     *
     * @param FormFieldsDtoInterface $dto
     * @param CoreRepository $repository
     * @return Model|false
     * @throws QrProfileNotFoundJsonException
     * @throws QrProfileUpdatedWithAnotherClientJsonException
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): Model|false
    {
        /** @var QrProfileUpdateApiDTO $dto */
        $qrProfileModel = $repository->getForEditModel(id: (int) $dto->qr_profile_id, useCache: true);

        if (empty($qrProfileModel)) {
            throw new QrProfileNotFoundJsonException();
        }

        /** @var QrProfile $qrProfileModel */

        $formDataArray = $dto->getFormFieldsArray();

        // Check permission:
        if ((int) $qrProfileModel->id_client !== (int) $formDataArray['id_client']) {
            throw new QrProfileUpdatedWithAnotherClientJsonException();
        }

        $qrDirPath = 'qr/'.$qrProfileModel->getKey();

        if (!empty($dto->photo_file)) {
            $formDataArray['photo_file_name'] = $this->fileService->processUploadFile(file: $dto->photo_file, publicDirPath: $qrDirPath, oldFileName: $qrProfileModel->photo_file_name, newFileName: '', useOriginalFileName: true);
        }

        if (!empty($dto->voice_message_file)) {
            $formDataArray['voice_message_file_name'] = $this->fileService->processUploadFile(file: $dto->voice_message_file, publicDirPath: $qrDirPath, oldFileName: $qrProfileModel->voice_message_file_name, newFileName: '', useOriginalFileName: true);
        }

        $updateQrProfile = $qrProfileModel->update(attributes: $formDataArray);

        if ($updateQrProfile) {
            return $qrProfileModel;
        }

        return false;
    }

    /**
     *  Скрытие записи о QR-профиле ("deleted_at" в таблице)
     *
     * @param $id
     * @param CoreRepository $repository
     * @return bool
     * @throws QrProfileNotFoundJsonException
     */
    public function processDestroy($id, CoreRepository $repository): bool
    {
        $qrProfileModel = $repository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($qrProfileModel)) {
            /** @var QrProfile $qrProfileModel */

            // Other logic...

            return (bool) $qrProfileModel->delete();
        }

        throw new QrProfileNotFoundJsonException();
    }
}
