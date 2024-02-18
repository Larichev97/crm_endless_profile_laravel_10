<?php

namespace App\Http\Requests\ContactForm;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class ContactFormStoreRequest extends FormRequest
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
            'firstname' => 'required|string|max:150',
            'lastname' => 'nullable|string|max:150',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|min:12|max:20|string',
            'comment' => 'nullable',
            'id_employee' => 'min:0|integer',
        ];
    }
}
