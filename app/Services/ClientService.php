<?php

namespace App\Services;

use App\Http\Requests\client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ClientService
{
    /**
     *  Создание записи о клиенте
     *
     * @param ClientStoreRequest $clientStoreRequest
     * @return bool
     */
    public function store(ClientStoreRequest $clientStoreRequest): bool
    {
        $params = $clientStoreRequest->all();

        $params['id_user_add'] = auth()->user()->id; // Кто создал запись

        if ($clientStoreRequest->hasFile('image') && !empty($clientStoreRequest->image)) {
            // *** Upload client photo:
            $photoName = time().'.'.$clientStoreRequest->image->extension();

            if (!File::exists(storage_path('app/images/clients'))) {
                File::makeDirectory(storage_path('app/images/clients'), 0777, true);
            }

            $clientStoreRequest->image->storeAs('images/clients', $photoName);

            if (!empty($photoName)) {
                $params = array_merge($params, ['photo_name' => $photoName]);
            }
            // ***
        }

        return (bool) Client::create($params);
    }

    /**
     *  Обновление записи о клиенте
     *
     * @param ClientUpdateRequest $clientUpdateRequest
     * @param Client $client
     * @return bool
     */
    public function update(ClientUpdateRequest $clientUpdateRequest, Client $client): bool
    {
        $params = $clientUpdateRequest->all();

        $params['id_user_update'] = auth()->user()->id; // Кто редактировал запись

        $oldPhotoName = $clientUpdateRequest->get('photo_name');

        if ($clientUpdateRequest->hasFile('image') && !empty($clientUpdateRequest->image)) {
            // Upload client photo:
            $photoName = time().'.'.$clientUpdateRequest->image->extension();

            if (!File::exists(storage_path('app/images/clients'))) {
                File::makeDirectory(storage_path('app/images/clients'), 0777, true);
            }

            $uploadPhoto = $clientUpdateRequest->image->storeAs('public/images/clients', $photoName);

            if ($uploadPhoto && !empty($photoName)) {
                $params = array_merge($params, ['photo_name' => $photoName]);
            }
        }

        $updateClient = $client->update($params);

        if ($updateClient) {
            // Проверка наличия файла и его удаление, если он существует:
            $oldPhotoPath = storage_path('app/public/images/clients/' . $oldPhotoName);

            if (!empty($photoName) && File::exists($oldPhotoPath)) {
                File::delete($oldPhotoPath); // Удаление файла
            }
        }

        return $updateClient;
    }
}
