<?php

namespace App\Services;

use App\DataTransferObjects\Client\ClientStoreDTO;
use App\DataTransferObjects\Client\ClientUpdateDTO;
use App\Models\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ClientService
{
    /**
     *  Создание записи о клиенте
     *
     * @param ClientStoreDTO $clientStoreDTO
     * @return bool
     */
    public function processStore(ClientStoreDTO $clientStoreDTO): bool
    {
        $formDataArray = $clientStoreDTO->getFormFieldsArray();

        // *** Upload client photo:
        if (!empty($clientStoreDTO->image) && $clientStoreDTO->image instanceof UploadedFile) {
            $photoName = time().'.'.$clientStoreDTO->image->extension();

            // Check directory "clients":
            if (!File::exists(storage_path('app/public/images/clients'))) {
                File::makeDirectory(storage_path('app/public/images/clients'), 0777, true);
            }

            $clientStoreDTO->image->storeAs('public/images/clients', $photoName);

            if (!empty($photoName) && File::exists(storage_path('app/public/images/clients/'.$photoName))) {
                $formDataArray['photo_name'] = $photoName;
            }
        }
        // ***

        return (bool) Client::create($formDataArray);
    }

    /**
     *  Обновление записи о клиенте
     *
     * @param ClientUpdateDTO $clientUpdateDTO
     * @return bool
     */
    public function processUpdate(ClientUpdateDTO $clientUpdateDTO): bool
    {
        $clientModel = Client::query()->findOrFail($clientUpdateDTO->id_client);

        $formDataArray = $clientUpdateDTO->getFormFieldsArray();

        // *** Upload client photo:
        if (!empty($clientUpdateRequest->image) && $clientUpdateDTO->image instanceof UploadedFile) {
            $photoName = time().'.'.$clientUpdateRequest->image->extension();

            // Check directory "clients":
            if (!File::exists(storage_path('app/public/images/clients'))) {
                File::makeDirectory(storage_path('app/public/images/clients'), 0777, true);
            }

            $uploadPhoto = $clientUpdateRequest->image->storeAs('public/images/clients', $photoName);

            if ($uploadPhoto && !empty($photoName)) {
                $formDataArray['photo_name'] = $photoName;
            }
        }
        // ***

        $updateClient = $clientModel->update($formDataArray);

        if ($updateClient) {
            $oldPhotoName = $clientUpdateDTO->photo_name;

            // Проверка наличия файла и его удаление, если он существует:
            $oldPhotoPath = storage_path('app/public/images/clients/' . $oldPhotoName);

            if (!empty($photoName) && File::exists($oldPhotoPath)) {
                File::delete($oldPhotoPath); // Удаление файла
            }
        }

        return $updateClient;
    }
}
