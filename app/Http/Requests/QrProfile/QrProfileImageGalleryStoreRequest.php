<?php

namespace App\Http\Requests\QrProfile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class QrProfileImageGalleryStoreRequest extends FormRequest
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
            'id_qr_profile' => 'required|min:1|integer',
            'is_active' => 'nullable|min:0|max:1',
            'gallery_photos.*' => 'nullable|image|mimes:jpg,jpeg,png,heic,heif|max:4048',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'gallery_photos.*.image' => 'Каждый файл в поле :attribute должен быть изображением',
            'gallery_photos.*.mimes' => 'Каждый файл в поле :attribute должен быть одним из следующих форматов: jpg, jpeg, png, heic, heif',
            'gallery_photos.*.max' => 'Каждый файл в поле :attribute не должен превышать размер 4 МБ',
        ];
    }
}
