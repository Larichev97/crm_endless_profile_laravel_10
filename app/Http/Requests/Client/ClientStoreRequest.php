<?php

namespace App\Http\Requests\client;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_country' => 'required|min:1|integer',
            'id_city' => 'required|min:1|integer',
            'phone_number' => 'required',
            'email' => 'required|email',
            'bdate' => 'required|date',
            'address' => '',
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'surname' => 'required|max:100',
            'manager_comment' => '',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
