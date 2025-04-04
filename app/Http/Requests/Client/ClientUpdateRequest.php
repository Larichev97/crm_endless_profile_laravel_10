<?php

namespace App\Http\Requests\Client;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->request->get('id_client');

        return [
            'id_status' => 'required|min:1|integer',
            'id_country' => 'required|min:1|integer',
            'id_city' => 'required|min:1|integer',
            'phone_number' => 'required|min:12|max:20|string|unique:clients,phone_number,'.$id,
            'email' => 'required|email|max:255|unique:clients,email,'.$id,
            'bdate' => 'nullable|date',
            'address' => 'nullable|max:255',
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'surname' => 'nullable|max:100',
            'manager_comment' => 'nullable',
            'photo_name' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_user_add' => 'required|min:0|integer',
            'id_user_update' => 'required|min:0|integer',
        ];
    }
}
