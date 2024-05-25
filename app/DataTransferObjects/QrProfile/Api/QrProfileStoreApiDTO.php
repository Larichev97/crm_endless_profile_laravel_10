<?php

namespace App\DataTransferObjects\QrProfile\Api;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Enums\QrStatusEnum;
use App\Http\Requests\QrProfile\Api\QrProfileStoreApiRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;

final readonly class QrProfileStoreApiDTO implements FormFieldsDtoInterface
{
    public int $id_status;
    public int $id_client;
    public int $id_country;
    public int $id_city;
    public string $birth_date;
    public string $death_date;
    public string $firstname;
    public string $lastname;
    public string $surname;
    public string $cause_death;
    public string $profession;
    public string $hobbies;
    public string $biography;
    public string $last_wish;
    public string $favourite_music_artist;
    public string $link;
    public string $geo_latitude;
    public string $geo_longitude;
    public string $photo_file_name;
    public UploadedFile|null $photo_file;
    public string $voice_message_file_name;
    public UploadedFile|null $voice_message_file;
    public string $qr_code_file_name;
    public int|null $id_user_add;
    public int|null $id_user_update;

    /**
     * @param QrProfileStoreApiRequest $qrProfileStoreApiRequest
     */
    public function __construct(QrProfileStoreApiRequest $qrProfileStoreApiRequest)
    {
        $this->id_status = QrStatusEnum::NEW->value;

        // *** TODO: change to Auth::user()->id after "Sanctum API":
        $firstUserId = (int) User::query()->first('id')->id;

        $this->id_user_add = (int) $qrProfileStoreApiRequest->validated('id_user_add') ?: $firstUserId;
        $this->id_user_update = (int) $qrProfileStoreApiRequest->validated('id_user_update') ?: $firstUserId;
        // ***

        $this->id_client = (int) $qrProfileStoreApiRequest->validated('id_client');
        $this->id_country = (int) $qrProfileStoreApiRequest->validated('id_country');
        $this->id_city = (int) $qrProfileStoreApiRequest->validated('id_city');
        $this->birth_date = (string) $qrProfileStoreApiRequest->validated('birth_date');
        $this->death_date = (string) $qrProfileStoreApiRequest->validated('death_date');
        $this->firstname = (string) $qrProfileStoreApiRequest->validated('firstname');
        $this->lastname = (string) $qrProfileStoreApiRequest->validated('lastname');
        $this->surname = (string) $qrProfileStoreApiRequest->validated('surname');
        $this->cause_death = (string) $qrProfileStoreApiRequest->validated('cause_death');
        $this->profession = (string) $qrProfileStoreApiRequest->validated('profession');
        $this->hobbies = (string) $qrProfileStoreApiRequest->validated('hobbies');
        $this->biography = (string) $qrProfileStoreApiRequest->validated('biography');
        $this->last_wish = (string) $qrProfileStoreApiRequest->validated('last_wish');
        $this->favourite_music_artist = (string) $qrProfileStoreApiRequest->validated('favourite_music_artist');
        $this->link = (string) $qrProfileStoreApiRequest->validated('link');
        $this->geo_latitude = (string) $qrProfileStoreApiRequest->validated('geo_latitude');
        $this->geo_longitude = (string) $qrProfileStoreApiRequest->validated('geo_longitude');
        $this->photo_file_name = (string) $qrProfileStoreApiRequest->validated('photo_file_name');
        $this->photo_file = $qrProfileStoreApiRequest->validated('photo_file');
        $this->voice_message_file_name = (string) $qrProfileStoreApiRequest->validated('voice_message_file_name');
        $this->voice_message_file = $qrProfileStoreApiRequest->validated('voice_message_file');
        $this->qr_code_file_name = (string) $qrProfileStoreApiRequest->validated('qr_code_file_name');
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
            'birth_date' => empty($this->birth_date) ? null : $this->birth_date,
            'death_date' => empty($this->death_date) ? null : $this->death_date,
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
