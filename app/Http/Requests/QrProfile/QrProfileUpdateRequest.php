<?php

namespace App\Http\Requests\QrProfile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class QrProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id_status' => 'required|min:1|integer',
            'id_client' => 'required|min:1|integer',
            'id_country' => 'required|min:1|integer',
            'id_city' => 'required|min:1|integer',
            'birth_date' => 'nullable|date',
            'death_date' => 'required|date',
            'firstname' => 'required|string|max:150',
            'lastname' => 'required|string|max:150',
            'surname' => 'nullable|string|max:150',
            'cause_death' => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:255',
            'hobbies' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'last_wish' => 'nullable|string|max:255',
            'favourite_music_artist' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'geo_latitude' => 'nullable|string|max:255',
            'geo_longitude' => 'nullable|string|max:255',
            'photo_file_name' => 'nullable|string|max:255',
            'photo_file' => 'nullable|image|mimes:jpg,jpeg,png,heic,heif|max:2048',
            'voice_message_file_name' => 'nullable|string|max:255',
            'voice_message_file' => 'nullable||mimes:mp3,wav,ogg,flac|max:10240',
            'qr_code_file_name' => 'nullable|string|max:255',
            'id_user_add' => 'required|min:1|integer',
            'id_user_update' => 'required|min:1|integer',
        ];
    }
}
