<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\Client\ClientStoreDTO;
use App\DataTransferObjects\Client\ClientUpdateDTO;
use App\Models\Client;
use App\Repositories\Client\ClientRepository;
use App\Services\FileService;
use Illuminate\Http\Request;

class ClientService
{
    /**
     *  Создание записи о клиенте
     *
     * @param ClientStoreDTO $clientStoreDTO
     * @param FileService $fileService
     * @return bool
     */
    public function processStore(ClientStoreDTO $clientStoreDTO, FileService $fileService): bool
    {
        $clientsImagesDirPath = 'images/clients';

        $formDataArray = $clientStoreDTO->getFormFieldsArray();

        $clientModel = Client::query()->create(attributes: $formDataArray);

        if ($clientModel) {
            $formFilesNamesArray['photo_name'] = $fileService->processUploadFile(file: $clientStoreDTO->image, publicDirPath: $clientsImagesDirPath, oldFileName: '', newFileName: '');

            return (bool) $clientModel->update(attributes: $formFilesNamesArray);
        }

        return false;
    }

    /**
     *  Обновление записи о клиенте
     *
     * @param ClientUpdateDTO $clientUpdateDTO
     * @param FileService $fileService
     * @param ClientRepository $clientRepository
     * @return bool
     */
    public function processUpdate(ClientUpdateDTO $clientUpdateDTO, FileService $fileService, ClientRepository $clientRepository): bool
    {
        $clientModel = $clientRepository->getForEditModel(id: (int) $clientUpdateDTO->id_client, useCache: true);

        if (empty($clientModel)) {
            return false;
        }

        /** @var Client $clientModel */

        $clientsImagesDirPath = 'images/clients';

        $formDataArray = $clientUpdateDTO->getFormFieldsArray();

        $formDataArray['photo_name'] = $fileService->processUploadFile(file: $clientUpdateDTO->image, publicDirPath: $clientsImagesDirPath, oldFileName: $clientUpdateDTO->photo_name);

        $updateClient = $clientModel->update(attributes: $formDataArray);

        return (bool) $updateClient;
    }

    /**
     *  Скрытие записи о Клиенте ("deleted_at" в таблице)
     *
     * @param $id
     * @param ClientRepository $clientRepository
     * @return bool
     */
    public function processDestroy($id, ClientRepository $clientRepository): bool
    {
        $clientModel = $clientRepository->getForEditModel(id: (int) $id, useCache: true);

        if (!empty($clientModel)) {
            /** @var Client $clientModel */

            // Other logic...

            return (bool) $clientModel->delete();
        }

        return false;
    }

    /**
     *  Формирование массива с полями о будущем Клиенте из формы "Обратной связи"
     *
     * @param Request $request
     * @return array
     */
    public function processGetContactFormData(Request $request): array
    {
        $contactFormData = [];

        // Сопадающие поля из формы "Обратной связи" для упрощения создания нового Клиента:
        $clientFields = ['firstname', 'lastname', 'email', 'phone_number'];

        foreach ($clientFields as $keyClientField => $clientFieldName) {
            $currentFieldValue = $request->get($clientFieldName, '');

            if (!empty($currentFieldValue)) {
                $contactFormData[$clientFieldName] = $currentFieldValue;
            }
        }

        return $contactFormData;
    }
}
