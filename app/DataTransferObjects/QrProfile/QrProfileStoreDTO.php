<?php

namespace App\DataTransferObjects\QrProfile;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\QrProfile\QrProfileStoreRequest;
use Illuminate\Http\UploadedFile;

final class QrProfileStoreDTO implements FormFieldsDtoInterface
{
    public readonly int $id_status;
    public readonly int $id_client;
    public readonly int $id_country;
    public readonly int $id_city;
    public readonly string $birth_date;
    public readonly string $death_date;
    public readonly string $firstname;
    public readonly string $lastname;
    public readonly string $surname;
    public readonly string $cause_death;
    public readonly string $profession;
    public readonly string $hobbies;
    public readonly string $biography;
    public readonly string $last_wish;
    public readonly string $favourite_music_artist;
    public readonly string $link;
    public readonly string $geo_latitude;
    public readonly string $geo_longitude;
    public readonly string $photo_file_name;
    public readonly UploadedFile|null $photo_file;
    public readonly string $voice_message_file_name;
    public readonly UploadedFile|null $voice_message_file;
    public readonly string $qr_code_file_name;
    public readonly int $id_user_add;
    public readonly int $id_user_update;

    /**
     * @param QrProfileStoreRequest $qrProfileStoreRequest
     */
    public function __construct(QrProfileStoreRequest $qrProfileStoreRequest)
    {
        $this->id_status = (int) $qrProfileStoreRequest->validated('id_status');
        $this->id_client = (int) $qrProfileStoreRequest->validated('id_client');
        $this->id_country = (int) $qrProfileStoreRequest->validated('id_country');
        $this->id_city = (int) $qrProfileStoreRequest->validated('id_city');
        $this->birth_date = (string) $qrProfileStoreRequest->validated('birth_date');
        $this->death_date = (string) $qrProfileStoreRequest->validated('death_date');
        $this->firstname = (string) $qrProfileStoreRequest->validated('firstname');
        $this->lastname = (string) $qrProfileStoreRequest->validated('lastname');
        $this->surname = (string) $qrProfileStoreRequest->validated('surname');
        $this->cause_death = (string) $qrProfileStoreRequest->validated('cause_death');
        $this->profession = (string) $qrProfileStoreRequest->validated('profession');
        $this->hobbies = (string) $qrProfileStoreRequest->validated('hobbies');
        $this->biography = (string) $qrProfileStoreRequest->validated('biography');
        $this->last_wish = (string) $qrProfileStoreRequest->validated('last_wish');
        $this->favourite_music_artist = (string) $qrProfileStoreRequest->validated('favourite_music_artist');
        $this->link = (string) $qrProfileStoreRequest->validated('link');
        $this->geo_latitude = (string) $qrProfileStoreRequest->validated('geo_latitude');
        $this->geo_longitude = (string) $qrProfileStoreRequest->validated('geo_longitude');
        $this->photo_file_name = (string) $qrProfileStoreRequest->validated('photo_file_name');
        $this->photo_file = $qrProfileStoreRequest->validated('photo_file');
        $this->voice_message_file_name = (string) $qrProfileStoreRequest->validated('voice_message_file_name');
        $this->voice_message_file = $qrProfileStoreRequest->validated('voice_message_file');
        $this->qr_code_file_name = (string) $qrProfileStoreRequest->validated('qr_code_file_name');
        $this->id_user_add = (int) $qrProfileStoreRequest->validated('id_user_add');
        $this->id_user_update = (int) $qrProfileStoreRequest->validated('id_user_update');
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        return [
            'id_status' => $this->id_status,
            'id_client' => $this->id_client,
            'id_country' => $this->id_country,
            'id_city' => $this->id_city,
            'birth_date' => $this->birth_date,
            'death_date' => $this->death_date,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'surname' => $this->surname,
            'cause_death' => $this->cause_death,
            'profession' => $this->profession,
            'hobbies' => $this->hobbies,
            'biography' => $this->biography,
            'last_wish' => $this->last_wish,
            'favourite_music_artist' => $this->favourite_music_artist,
            'link' => $this->link,
            'geo_latitude' => $this->geo_latitude,
            'geo_longitude' => $this->geo_longitude,
            'photo_file_name' => $this->photo_file_name,
            'voice_message_file_name' => $this->voice_message_file_name,
            'qr_code_file_name' => $this->qr_code_file_name,
            'id_user_add' => $this->id_user_add,
            'id_user_update' => $this->id_user_update,
        ];
    }
}
