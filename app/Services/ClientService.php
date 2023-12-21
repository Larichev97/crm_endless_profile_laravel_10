<?php

namespace App\Services;

use App\DataTransferObjects\Client\ClientStoreDTO;
use App\DataTransferObjects\Client\ClientUpdateDTO;
use App\Models\Client;
use App\Repositories\Client\ClientRepository;

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

        $clientModel = Client::query()->create($formDataArray);

        if ($clientModel) {
            $formFilesNamesArray['photo_name'] = $fileService->processUploadFile($clientStoreDTO->image, $clientsImagesDirPath);

            return (bool) $clientModel->update($formFilesNamesArray);
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
        $clientModel = $clientRepository->getForEditModel((int) $clientUpdateDTO->id_client, true);

        if (empty($clientModel)) {
            return false;
        }

        $clientsImagesDirPath = 'images/clients';

        $formDataArray = $clientUpdateDTO->getFormFieldsArray();

        $formDataArray['photo_name'] = $fileService->processUploadFile($clientUpdateDTO->image, $clientsImagesDirPath);

        $updateClient = $clientModel->update($formDataArray);

        if ($updateClient) {
            $fileService->processDeleteOldFile($clientUpdateDTO->photo_name, $clientsImagesDirPath);
        }

        return $updateClient;
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
        $clientModel = $clientRepository->getForEditModel((int) $id, true);

        if (!empty($clientModel)) {
            /** @var Client $clientModel */

            // Other logic...

            return (bool) $clientModel->delete();
        }

        return false;
    }
}
