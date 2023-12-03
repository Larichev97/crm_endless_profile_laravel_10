<?php

namespace App\DataTransferObjects\Client;

use App\Http\Requests\Client\ClientUpdateRequest;
use Illuminate\Http\UploadedFile;

final class ClientUpdateDTO
{
    public readonly int $id_status;
    public readonly int $id_country;
    public readonly int $id_city;
    public readonly string $phone_number;
    public readonly string $email;
    public readonly string $bdate;
    public readonly string|null $address;
    public readonly string $firstname;
    public readonly string $lastname;
    public readonly string $surname;
    public readonly string $manager_comment;
    public readonly string $photo_name;
    public readonly UploadedFile|null $image;
    public readonly int $id_user_add;
    public readonly int $id_user_update;

    /**
     * @param ClientUpdateRequest $clientStoreRequest
     */
    public function __construct(ClientUpdateRequest $clientStoreRequest, public readonly int $id_client)
    {
        $this->id_status = (int) $clientStoreRequest->validated('id_status');
        $this->id_country = (int) $clientStoreRequest->validated('id_country');
        $this->id_city = (int) $clientStoreRequest->validated('id_city');
        $this->phone_number = (string) $clientStoreRequest->validated('phone_number');
        $this->email = (string) $clientStoreRequest->validated('email');
        $this->bdate = (string) $clientStoreRequest->validated('bdate');
        $this->address = (string) $clientStoreRequest->validated('address');
        $this->firstname = (string) $clientStoreRequest->validated('firstname');
        $this->lastname = (string) $clientStoreRequest->validated('lastname');
        $this->surname = (string) $clientStoreRequest->validated('surname');
        $this->manager_comment = (string) $clientStoreRequest->validated('manager_comment');
        $this->photo_name = (string) $clientStoreRequest->validated('photo_name');
        $this->image = $clientStoreRequest->validated('image');
        $this->id_user_add = (int) $clientStoreRequest->validated('id_user_add');
        $this->id_user_update = (int) $clientStoreRequest->validated('id_user_update');
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'id_status' => $this->id_status,
            'id_country' => $this->id_country,
            'id_city' => $this->id_city,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'bdate' => $this->bdate,
            'address' => $this->address,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'surname' => $this->surname,
            'manager_comment' => $this->manager_comment,
            'photo_name' => $this->photo_name,
            'id_user_add' => $this->id_user_add,
            'id_user_update' => $this->id_user_update,
        ];
    }
}
