<?php

namespace App\Services\CrudActionsServices\Api;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\DataTransferObjects\QrProfile\QrProfileStoreDTO;
use App\DataTransferObjects\QrProfile\QrProfileUpdateDTO;
use App\Exceptions\QrProfile\QrProfileNotFoundJsonException;
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
        /** @var QrProfileStoreDTO $dto */

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
     */
    public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): Model|false
    {
        /** @var QrProfileUpdateDTO $dto */
        $qrProfileModel = $repository->getForEditModel(id: (int) $dto->id_qr, useCache: true);

        if (empty($qrProfileModel)) {
            throw new QrProfileNotFoundJsonException();
        }

        $formDataArray = $dto->getFormFieldsArray();

        /** @var QrProfile $qrProfileModel */
        $qrDirPath = 'qr/'.$qrProfileModel->getKey();

        $formDataArray['photo_file_name'] = $this->fileService->processUploadFile(file: $dto->photo_file, publicDirPath: $qrDirPath, oldFileName: $dto->photo_file_name, newFileName: '', useOriginalFileName: true);
        $formDataArray['voice_message_file_name'] = $this->fileService->processUploadFile(file: $dto->voice_message_file, publicDirPath: $qrDirPath, oldFileName: $dto->voice_message_file_name, newFileName: '', useOriginalFileName: true);

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
