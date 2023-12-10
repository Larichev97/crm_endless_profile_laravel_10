<?php

namespace App\DataTransferObjects\QrProfile;

use App\Http\Requests\QrProfile\QrProfileUpdateRequest;
use Illuminate\Http\UploadedFile;

final class QrProfileUpdateDTO
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
    public readonly int $id_user_add;
    public readonly int $id_user_update;

    /**
     * @param QrProfileUpdateRequest $qrProfileUpdateRequest
     * @param int $id_qr
     */
    public function __construct(QrProfileUpdateRequest $qrProfileUpdateRequest, public readonly int $id_qr)
    {
        $this->id_status = (int) $qrProfileUpdateRequest->validated('id_status');
        $this->id_client = (int) $qrProfileUpdateRequest->validated('id_client');
        $this->id_country = (int) $qrProfileUpdateRequest->validated('id_country');
        $this->id_city = (int) $qrProfileUpdateRequest->validated('id_city');
        $this->birth_date = (string) $qrProfileUpdateRequest->validated('birth_date');
        $this->death_date = (string) $qrProfileUpdateRequest->validated('death_date');
        $this->firstname = (string) $qrProfileUpdateRequest->validated('firstname');
        $this->lastname = (string) $qrProfileUpdateRequest->validated('lastname');
        $this->surname = (string) $qrProfileUpdateRequest->validated('surname');
        $this->cause_death = (string) $qrProfileUpdateRequest->validated('cause_death');
        $this->profession = (string) $qrProfileUpdateRequest->validated('profession');
        $this->hobbies = (string) $qrProfileUpdateRequest->validated('hobbies');
        $this->biography = (string) $qrProfileUpdateRequest->validated('biography');
        $this->last_wish = (string) $qrProfileUpdateRequest->validated('last_wish');
        $this->favourite_music_artist = (string) $qrProfileUpdateRequest->validated('favourite_music_artist');
        $this->link = (string) $qrProfileUpdateRequest->validated('link');
        $this->geo_latitude = (string) $qrProfileUpdateRequest->validated('geo_latitude');
        $this->geo_longitude = (string) $qrProfileUpdateRequest->validated('geo_longitude');
        $this->photo_file_name = (string) $qrProfileUpdateRequest->validated('photo_file_name');
        $this->photo_file = $qrProfileUpdateRequest->validated('photo_file');
        $this->voice_message_file_name = (string) $qrProfileUpdateRequest->validated('voice_message_file_name');
        $this->voice_message_file = $qrProfileUpdateRequest->validated('voice_message_file');
        $this->id_user_add = (int) $qrProfileUpdateRequest->validated('id_user_add');
        $this->id_user_update = (int) $qrProfileUpdateRequest->validated('id_user_update');
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
            'id_user_add' => $this->id_user_add,
            'id_user_update' => $this->id_user_update,
        ];
    }
}
