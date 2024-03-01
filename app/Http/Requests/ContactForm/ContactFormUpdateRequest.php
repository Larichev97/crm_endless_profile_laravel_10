<?php

namespace App\Http\Requests\ContactForm;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class ContactFormUpdateRequest extends FormRequest
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
            'id_employee' => 'required|min:1|integer',
        ];
    }

    /**
     *  UA errors translations
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'id_status.required' => 'Поле Статус є обов\'язковим для заповнення.',
            'id_status.min' => 'Мінімальне значення поля Статус :min.',
            'id_status.integer' => 'Поле Статус повинно бути цілим числом.',
            'firstname.required' => 'Поле Ім\'я є обов\'язковим для заповнення.',
            'firstname.string' => 'Поле Ім\'я повинно бути рядком.',
            'firstname.max' => 'Максимальна довжина поля Ім\'я :max символів.',
            'lastname.string' => 'Поле Прізвище повинно бути рядком.',
            'lastname.max' => 'Максимальна довжина поля Прізвище :max символів.',
            'email.required' => 'Поле Email є обов\'язковим для заповнення.',
            'email.email' => 'Поле Email повинно бути валідним email адресою.',
            'email.max' => 'Максимальна довжина поля Email :max символів.',
            'phone_number.required' => 'Поле Номер телефону є обов\'язковим для заповнення.',
            'phone_number.min' => 'Мінімальна довжина поля Номер телефону :min символів.',
            'phone_number.max' => 'Максимальна довжина поля Номер телефону :max символів.',
            'phone_number.string' => 'Поле Номер телефону повинно бути рядком.',
            'comment.nullable' => 'Поле Коментар може бути порожнім.',
            'id_employee.required' => 'Поле ID працівника є обов\'язковим для заповнення.',
            'id_employee.min' => 'Мінімальне значення поля ID працівника :min.',
            'id_employee.integer' => 'Поле ID працівника повинно бути цілим числом.',
        ];
    }
}
