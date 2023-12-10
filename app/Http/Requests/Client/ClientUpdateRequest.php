<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

final class ClientUpdateRequest extends FormRequest
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
            'id_status' => 'required|min:1|integer',
            'id_country' => 'required|min:1|integer',
            'id_city' => 'required|min:1|integer',
            'phone_number' => 'required',
            'email' => 'required|email|max:255',
            'bdate' => 'required|date',
            'address' => 'nullable|max:255',
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'surname' => 'required|max:100',
            'manager_comment' => 'nullable',
            'photo_name' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_user_add' => 'required|min:0|integer',
            'id_user_update' => 'required|min:0|integer',
        ];
    }
}
