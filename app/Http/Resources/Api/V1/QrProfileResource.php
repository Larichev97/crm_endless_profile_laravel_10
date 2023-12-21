<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QrProfileResource extends JsonResource
{
    /**
     * @var string Обёртка данных в ответе ("data": ....)
     */
    public static $wrap = 'data';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_country' => $this->id_country,
            'id_city' => $this->id_city,
            'birth_date' => (string) $this->birthDateFormatted,
            'death_date' => (string) $this->deathDateFormatted,
            'full_name' => (string) $this->fullName,
            'firstname' => (string) $this->firstname,
            'lastname' => (string) $this->lastname,
            'surname' => (string) $this->surname,
            'cause_death' => (string) $this->cause_death,
            'profession' => (string) $this->profession,
            'hobbies' => (string) $this->hobbies,
            'biography' => (string) $this->biography,
            'last_wish' => (string) $this->last_wish,
            'favourite_music_artist' => (string) $this->favourite_music_artist,
            'link' => (string) $this->link,
            'geo_latitude' => (string) $this->geo_latitude,
            'geo_longitude' => (string) $this->geo_longitude,
            'photo_file_name' => !empty($this->photo_file_name) ? (string) $this->photo_file_name : '',
            'photo_file_path' => $this->getPhotoPath(),
            'voice_message_file_name' => !empty($this->voice_message_file_name) ? (string) $this->voice_message_file_name : '',
            'voice_message_file_path' => $this->getVoiceMessagePath(),
            'qr_code_file_name' => !empty($this->qr_code_file_name) ? (string) $this->qr_code_file_name : '',
            'qr_code_file_path' => $this->getQrCodePath(),
        ];
    }
}
