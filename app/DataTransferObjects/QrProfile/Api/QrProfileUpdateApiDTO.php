<?php

namespace App\DataTransferObjects\QrProfile\Api;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Http\Requests\QrProfile\Api\QrProfileUpdateApiRequest;
use Illuminate\Http\UploadedFile;

final readonly class QrProfileUpdateApiDTO implements FormFieldsDtoInterface
{
    public int $id_client;
    public int|null $id_country;
    public int|null $id_city;
    public string|null $birth_date;
    public string|null $death_date;
    public string|null $firstname;
    public string|null $lastname;
    public string|null $surname;
    public string|null $cause_death;
    public string|null $profession;
    public string|null $hobbies;
    public string|null $biography;
    public string|null $last_wish;
    public string|null $favourite_music_artist;
    public string|null $link;
    public string|null $geo_latitude;
    public string|null $geo_longitude;
    public UploadedFile|null $photo_file;
    public UploadedFile|null $voice_message_file;
    public int|null $id_user_add;
    public int|null $id_user_update;

    /**
     * @param QrProfileUpdateApiRequest $qrProfileUpdateApiRequest
     * @param int $qr_profile_id
     */
    public function __construct(QrProfileUpdateApiRequest $qrProfileUpdateApiRequest, public int $qr_profile_id)
    {
        // *** TODO: change to Auth::user()->id after "Sanctum API":
        $this->id_user_add = (int) $qrProfileUpdateApiRequest->validated('id_user_add') ?: null;
        $this->id_user_update = (int) $qrProfileUpdateApiRequest->validated('id_user_update') ?: null;
        // ***

        $this->id_client = (int) $qrProfileUpdateApiRequest->validated('id_client');
        $this->id_country = $qrProfileUpdateApiRequest->validated('id_country') !== null ? (int) $qrProfileUpdateApiRequest->validated('id_country') : null;
        $this->id_city = $qrProfileUpdateApiRequest->validated('id_city') !== null ? (int) $qrProfileUpdateApiRequest->validated('id_city') : null;
        $this->birth_date = $qrProfileUpdateApiRequest->validated('birth_date') !== null ? trim($qrProfileUpdateApiRequest->validated('birth_date')) : null;
        $this->death_date = $qrProfileUpdateApiRequest->validated('death_date') !== null ? trim($qrProfileUpdateApiRequest->validated('death_date')) : null;
        $this->firstname = $qrProfileUpdateApiRequest->validated('firstname') !== null ? trim($qrProfileUpdateApiRequest->validated('firstname')) : null;
        $this->lastname = $qrProfileUpdateApiRequest->validated('lastname') !== null ? trim($qrProfileUpdateApiRequest->validated('lastname')) : null;
        $this->surname = $qrProfileUpdateApiRequest->validated('surname') !== null ? trim($qrProfileUpdateApiRequest->validated('surname')) : null;
        $this->cause_death = $qrProfileUpdateApiRequest->validated('cause_death') !== null ? trim($qrProfileUpdateApiRequest->validated('cause_death')) : null;
        $this->profession = $qrProfileUpdateApiRequest->validated('profession') !== null ? trim($qrProfileUpdateApiRequest->validated('profession')) : null;
        $this->hobbies = $qrProfileUpdateApiRequest->validated('hobbies') !== null ? trim($qrProfileUpdateApiRequest->validated('hobbies')) : null;
        $this->biography = $qrProfileUpdateApiRequest->validated('biography') !== null ? $qrProfileUpdateApiRequest->validated('biography') : null;
        $this->last_wish = $qrProfileUpdateApiRequest->validated('last_wish') !== null ? trim($qrProfileUpdateApiRequest->validated('last_wish')) : null;
        $this->favourite_music_artist = $qrProfileUpdateApiRequest->validated('favourite_music_artist') !== null ? trim($qrProfileUpdateApiRequest->validated('favourite_music_artist')) : null;
        $this->link = $qrProfileUpdateApiRequest->validated('link') !== null ? trim($qrProfileUpdateApiRequest->validated('link')) : null;
        $this->geo_latitude = $qrProfileUpdateApiRequest->validated('geo_latitude') !== null ? trim($qrProfileUpdateApiRequest->validated('geo_latitude')) : null;
        $this->geo_longitude = $qrProfileUpdateApiRequest->validated('geo_longitude') !== null ? trim($qrProfileUpdateApiRequest->validated('geo_longitude')) : null;
        $this->photo_file = $qrProfileUpdateApiRequest->validated('photo_file') !== null ? $qrProfileUpdateApiRequest->validated('photo_file') : null;
        $this->voice_message_file = $qrProfileUpdateApiRequest->validated('voice_message_file') !== null ? $qrProfileUpdateApiRequest->validated('voice_message_file') : null;
    }

    /**
     * @return array
     */
    public function getFormFieldsArray(): array
    {
        $updateFieldsData = [];

        $propertiesArray = get_object_vars($this);

        if (!empty($propertiesArray)) {
            foreach ($propertiesArray as $propertyName => $propertyValue) {
                if ($propertyValue !== null) {
                    $updateFieldsData[$propertyName] = $propertyValue;
                }
            }
        }

        return $updateFieldsData;
    }
}
