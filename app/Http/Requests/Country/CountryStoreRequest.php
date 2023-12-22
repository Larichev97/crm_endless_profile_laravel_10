<?php

namespace App\Http\Requests\Country;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class CountryStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'iso_code' => 'required|string|max:10',
            'is_active' => 'nullable|min:0|max:1',
            'flag_file_name' => 'nullable|string|max:255',
            'flag_file' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ];
    }
}
